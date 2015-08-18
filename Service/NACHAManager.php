<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 1/23/15
 * Time: 9:52 AM
 */

namespace WellsFargo\ACHBundle\Service;

use Exception;
use WellsFargo\ACHBundle\Model\NACHAFile;
use WellsFargo\ACHBundle\Model\NACHAOriginationRejectFile;


class NACHAManager {

    /** @var  string  */
    private $bankrt;

    /** @var  string  */
    private $companyId;

    /** @var  string  */
    private $applicationId;

    /** @var  string  */
    private $fileId;

    /** @var  string  */
    private $originatingBank;

    /** @var  string  */
    private $companyName;

    /** @var string $wellsFargoTransmissionHost */
    private $wellsFargoTransmissionHost;

    /** @var string $wellsFargoTransmissionUsername */
    private $wellsFargoTransmissionUsername;

    /** @var string $wellsFargoTransmissionPrivateKey */
    private $wellsFargoTransmissionPrivateKey;

    /** @var string $wellsFargoTransmissionPrivateKeyPassword */
    private $wellsFargoTransmissionPrivateKeyPassword;

    /** @var string $wellsFargoTransmissionPublicKey */
    private $wellsFargoTransmissionPublicKey;

    /** @var string $wellsFargoTransmissionInboundFolder */
    private $wellsFargoTransmissionInboundFolder;

    /** @var string $wellsFargoTransmissionOutboundFolder */
    private $wellsFargoTransmissionOutboundFolder;

    /** @var string $wellsFargoTransmissionReturnsReportFolder */
    private $wellsFargoTransmissionReturnsReportFolder;

    /** @var string $wellsFargoTransmissionArchiveInboundFolder */
    private $wellsFargoTransmissionArchiveInboundFolder;

    /** @var string $wellsFargoTransmissionArchiveOutboundFolder */
    private $wellsFargoTransmissionArchiveOutboundFolder;

    /** @var string $wellsFargoTransmissionArchiveReturnsReportFolder */
    private $wellsFargoTransmissionArchiveReturnsReportFolder;


    /** @var $logger \Monolog\Logger */
    private $logger;

    public function __construct($routingNumber, $companyId, $applicationId, $fileId, $originatingBank, $companyName, $wellsFargoTransmissionHost, $wellsFargoTransmissionUsername, $wellsFargoTransmissionPrivateKey, $wellsFargoTransmissionPrivateKeyPassword, $wellsFargoTransmissionPublicKey, $wellsFargoTransmissionInboundFolder, $wellsFargoTransmissionOutboundFolder, $wellsFargoTransmissionReturnsReportFolder, $wellsFargoTransmissionArchiveInboundFolder, $wellsFargoTransmissionArchiveOutboundFolder, $wellsFargoTransmissionArchiveReturnsReportFolder,  $logger)
    {
        $this->bankrt = $routingNumber;
        $this->companyId = $companyId;
        $this->applicationId = $applicationId;
        $this->fileId = $fileId;
        $this->originatingBank = $originatingBank;
        $this->companyName = $companyName;
        $this->logger = $logger;

        $this->wellsFargoTransmissionHost = $wellsFargoTransmissionHost;
        $this->wellsFargoTransmissionUsername = $wellsFargoTransmissionUsername;
        $this->wellsFargoTransmissionPrivateKey = $wellsFargoTransmissionPrivateKey;
        $this->wellsFargoTransmissionPrivateKeyPassword = $wellsFargoTransmissionPrivateKeyPassword;
        $this->wellsFargoTransmissionPublicKey = $wellsFargoTransmissionPublicKey;

        $this->wellsFargoTransmissionInboundFolder = $wellsFargoTransmissionInboundFolder;
        $this->wellsFargoTransmissionOutboundFolder = $wellsFargoTransmissionOutboundFolder;
        $this->wellsFargoTransmissionReturnsReportFolder = $wellsFargoTransmissionReturnsReportFolder;

        $this->wellsFargoTransmissionArchiveInboundFolder = $wellsFargoTransmissionArchiveInboundFolder;
        $this->wellsFargoTransmissionArchiveOutboundFolder = $wellsFargoTransmissionArchiveOutboundFolder;
        $this->wellsFargoTransmissionArchiveReturnsReportFolder = $wellsFargoTransmissionArchiveReturnsReportFolder;
    }

    /**
     * Generates a fresh nacha file to use.
     *
     * @return NACHAFile
     */
    public function createNACHAFile() {
        return new NACHAFile($this->bankrt, $this->companyId, $this->applicationId, $this->fileId, $this->originatingBank, $this->companyName);
    }


    /**
     * Uploads any ach payments that we have waiting for the day. This is called from the command line.
     *
     * @return string
     * @throws Exception
     */
    public function uploadNACHAFile(NACHAFile $nachaFile)
    {
        $this->logger->notice('Starting Upload of Wells Fargo NACHA File');


        $connection = ssh2_connect($this->wellsFargoTransmissionHost, 22, array('hostkey'=>'ssh-rsa'));

        if (!ssh2_auth_pubkey_file($connection, $this->wellsFargoTransmissionUsername, $this->wellsFargoTransmissionPublicKey, $this->wellsFargoTransmissionPrivateKey, $this->wellsFargoTransmissionPrivateKeyPassword)) {
            $this->logger->critical('Could not connect to send the NACHA file to wells fargo');
            return;
        }

        $sftp = ssh2_sftp($connection);

        $inboundConnectionURL = 'ssh2.sftp://'.$sftp.'/'.$this->wellsFargoTransmissionInboundFolder;
        $inboundFolderHandle = opendir($inboundConnectionURL);


        $now = new \Datetime('now', new \DateTimeZone('PST8PDT'));
        $now->setTime(0, 0, 0);

        $fivePm = new \DateTime('now', new \DateTimeZone('PST8PDT'));
        $fivePm->setTime(17, 0, 0);

        if ($now > $fivePm) {
            $now->setTime(0, 0, 0);
            $now = $now->add(new \DateInterval('P1D'));
        }

        $fileModifier = 'A';

        while (false !== ($file = readdir($inboundFolderHandle))) {
            $fileCreationTime = new \DateTime(date("F d Y H:i:s.", filemtime($inboundConnectionURL.'/'.$file)), new \DateTimeZone('PST8PDT'));
            $fileCreationTime->setTime(0, 0, 0);
            if ($fileCreationTime == $now) {
                $fileModifier++;
            }
            if ($fileModifier == 'AA') {
                $fileModifier = "A";
            }
        }

        $nachaFile->setFileModifier($fileModifier);

        $nachaFileContents = $nachaFile->generateFileContents();

        if (is_null($nachaFileContents)) {
            $this->logger->notice('The nacha file had no contents.');
            return;
        }

        $sftpStream = @fopen($inboundConnectionURL.'/nacha-'.date('M-d-Y').'.txt', 'w');

        try {
            if (!$sftpStream) {
                throw new Exception("Could not open remote sftp file for NACHA writing.");
            }

            if (@fwrite($sftpStream, $nachaFileContents) === false) {
                throw new Exception("Could not send data from the nacha file");
            }

            fclose($sftpStream);
        } catch (Exception $e) {
            $this->logger->critical('Could not send the NACHA file to wells fargo: '. $e->getMessage());
            fclose($sftpStream);
            return;
        }

        $this->logger->notice('Finished Upload of Wells Fargo NACHA File');
    }


    #endregion

    #region "Reports"

    /**
     * @param \DateTime $dateTime
     * @param bool $searchArchives
     */
    public function processWellsFargoReportForDateTime(\DateTime $dateTime, $searchArchives = false)
    {
        $this->processWellsFargoReportForDateTimes(array($dateTime), $searchArchives);
    }

    /**
     *  Processes the Wells Fargo report for specific times.
     *
     * @param array $dateTimes
     * @param bool $searchArchives Whether or not to search archived files
     * @return array
     */
    public function processWellsFargoReportForDateTimes(array $dateTimes, $searchArchives = false)
    {
        $this->logger->notice('Starting Processing of Wells Fargo NACHA Report');


        $connection = ssh2_connect($this->wellsFargoTransmissionHost, 22, array('hostkey'=>'ssh-rsa'));

        if (!ssh2_auth_pubkey_file($connection, $this->wellsFargoTransmissionUsername, $this->wellsFargoTransmissionPublicKey, $this->wellsFargoTransmissionPrivateKey, $this->wellsFargoTransmissionPrivateKeyPassword)) {
            $this->logger->critical('Could not connect to grab the report file from wells fargo');
            return null;
        }

        $sftp = ssh2_sftp($connection);

        $returnsReportConnectionURL = 'ssh2.sftp://'.$sftp.'/'.$this->wellsFargoTransmissionReturnsReportFolder;
        $originationFilesToProcess = $this->processWellsFargoReturnsReportForURLAndDates($returnsReportConnectionURL, $dateTimes);
        if($searchArchives) {
            $returnsReportArchiveConnectionURL = 'ssh2.sftp://'.$sftp.'/'.$this->wellsFargoTransmissionArchiveReturnsReportFolder;
            $originationArchiveFilesToProcess = $this->processWellsFargoReturnsReportForURLAndDates($returnsReportArchiveConnectionURL, $dateTimes);

            if(count($originationFilesToProcess) == 0) {
                $originationFilesToProcess = $originationArchiveFilesToProcess;
            } else if(count($originationArchiveFilesToProcess) != 0) {
                array_merge($originationFilesToProcess, $originationArchiveFilesToProcess);
            }
        }

        $this->logger->notice('Finished Processing of Wells Fargo NACHA Report');

        return $originationFilesToProcess;
    }


    /**
     * @param $returnsReportConnectionURL
     * @param array $dateTimes
     * @return array
     */
    private function processWellsFargoReturnsReportForURLAndDates($returnsReportConnectionURL, array $dateTimes)
    {

        $outboundFolderHandle = opendir($returnsReportConnectionURL);

        /** @var \DateTime $dateTime */
        foreach($dateTimes as &$dateTime) {
            $dateTime->setTimezone(new \DateTimeZone('PST8PDT'));
            $dateTime->setTime(0, 0, 0);
        }
        $originationFilesToProcess = array();

        while (false !== ($file = readdir($outboundFolderHandle))) {
            $originationRejectFile = new NACHAOriginationRejectFile();

            $sftpStream = @fopen($returnsReportConnectionURL.'/'.$file, 'r');

            try {
                if (!$sftpStream) {
                    $this->logger->critical('Could not open remote sftp file for NACHA report reading: '.$returnsReportConnectionURL.'/'.$file);
                    continue;
                }

                $contents = fread($sftpStream, filesize($returnsReportConnectionURL.'/'.$file));

                fclose($sftpStream);

                $originationRejectFile->parseString($contents);
            } catch (Exception $e) {
                $this->logger->critical('Could not read the NACHA report file from wells fargo: '. $e->getMessage().'  :  '.$returnsReportConnectionURL.'/'.$file);
                fclose($sftpStream);
                continue;
            }

            $creationDate = $originationRejectFile->getFileHeader()->getFileCreationDateTime();
            $creationDate->setTime(0, 0, 0);

            if(in_array($creationDate, $dateTimes)) {
                $originationFilesToProcess[] = $originationRejectFile;
            }
        }

        closedir($outboundFolderHandle);

        return $originationFilesToProcess;
    }


}
