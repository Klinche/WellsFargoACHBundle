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


class NachaManager {

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

    /** @var string $wellsFargoTransmissionArchiveOutFolder */
    private $wellsFargoTransmissionArchiveOutFolder;


    /** @var $logger \Monolog\Logger */
    private $logger;

    public function __construct($routingNumber, $companyId, $applicationId, $fileId, $originatingBank, $companyName, $wellsFargoTransmissionHost, $wellsFargoTransmissionUsername, $wellsFargoTransmissionPrivateKey, $wellsFargoTransmissionPrivateKeyPassword, $wellsFargoTransmissionPublicKey, $wellsFargoTransmissionInboundFolder, $wellsFargoTransmissionOutboundFolder, $wellsFargoTransmissionArchiveOutFolder, $logger)
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
        $this->wellsFargoTransmissionArchiveOutFolder = $wellsFargoTransmissionArchiveOutFolder;

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
        $this->logger->info('Starting Upload of Wells Fargo NACHA File');


        $connection = ssh2_connect($this->wellsFargoTransmissionHost, 22, array('hostkey'=>'ssh-rsa'));

        if (!ssh2_auth_pubkey_file($connection, $this->wellsFargoTransmissionUsername, $this->wellsFargoTransmissionPublicKey, $this->wellsFargoTransmissionPrivateKey, $this->wellsFargoTransmissionPrivateKeyPassword)) {
            $this->logger->critical('Could not connect to send the NACHA file to wells fargo');
            return;
        }

        $sftp = ssh2_sftp($connection);

        $inboundConnectionURL = 'ssh2.sftp://'.$sftp.'/'.$this->wellsFargoTransmissionInboundFolder;
        $inboundFolderHandle = opendir($inboundConnectionURL);

        $script_tz = date_default_timezone_get();
        date_default_timezone_set('PST8PDT');
        $now = new \Datetime('now', new \DateTimeZone('PST8PDT'));
        $now->setTime(0, 0, 0);

        $fileModifier = 'A';

        while (false !== ($file = readdir($inboundFolderHandle))) {
            $fileCreationTime = new \DateTime(date("F d Y H:i:s.", filemtime($inboundConnectionURL.'/'.$file)), new \DateTimeZone('Etc/GMT-7'));
            $fileCreationTime->setTime(0, 0, 0);
            if ($fileCreationTime == $now) {
                $fileModifier++;
            }
        }

        $nachaFile->setFileModifier($fileModifier);

        $nachaFileContents = $nachaFile->generateFileContents();

        date_default_timezone_set($script_tz);

        if (is_null($nachaFileContents)) {
            $this->logger->info('The nacha file had no contents.');
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

        $this->logger->info('Finished Upload of Wells Fargo NACHA File');
    }


    #endregion

    #region "Reports"

    /**
     * t
     */
    public function processWellsFargoReportForDateTime(\DateTime $dateTime)
    {
        $this->logger->info('Starting Processing of Wells Fargo NACHA Report');


        $connection = ssh2_connect($this->wellsFargoTransmissionHost, 22, array('hostkey'=>'ssh-rsa'));

        if (!ssh2_auth_pubkey_file($connection, $this->wellsFargoTransmissionUsername, $this->wellsFargoTransmissionPublicKey, $this->wellsFargoTransmissionPrivateKey, $this->wellsFargoTransmissionPrivateKeyPassword)) {
            $this->logger->critical('Could not connect to grab the report file from wells fargo');
            return null;
        }

        $sftp = ssh2_sftp($connection);

        $outboundConnectionURL = 'ssh2.sftp://'.$sftp.'/'.$this->wellsFargoTransmissionOutboundFolder;
        $outboundFolderHandle = opendir($outboundConnectionURL);

        $script_tz = date_default_timezone_get();
        date_default_timezone_set('PST8PDT');

        $dateTime->setTimezone(new \DateTimeZone('PST8PDT'));
        $dateTime->setTime(0, 0, 0);

        $originationFilesToProcess = array();

        while (false !== ($file = readdir($outboundFolderHandle))) {
            $fileCreationTime = new \DateTime(date("F d Y H:i:s.", filemtime($outboundConnectionURL.'/'.$file)), new \DateTimeZone('PST8PDT'));
            $fileCreationTime->setTime(0, 0, 0);
            if ($fileCreationTime == $dateTime) {
                $originationRejectFile = new NACHAOriginationRejectFile();

                $sftpStream = @fopen($outboundConnectionURL.'/'.$file, 'r');

                try {
                    if (!$sftpStream) {
                        $this->logger->critical('Could not open remote sftp file for NACHA report reading: '.$outboundConnectionURL.'/'.$file);
                        continue;
                    }

                    $contents = fread($sftpStream, filesize($outboundConnectionURL.'/'.$file));
                    fclose($sftpStream);

                    $originationRejectFile->parseString($contents);
                } catch (Exception $e) {
                    $this->logger->critical('Could not read the NACHA report file to wells fargo: '. $e->getMessage().'  :  '.$outboundConnectionURL.'/'.$file);
                    fclose($sftpStream);
                    continue;
                }

                $originationFilesToProcess[] = $originationRejectFile;
            }
        }

        date_default_timezone_set($script_tz);

        $this->logger->info('Finished Processing of Wells Fargo NACHA Report');

        return $originationFilesToProcess;
    }

}