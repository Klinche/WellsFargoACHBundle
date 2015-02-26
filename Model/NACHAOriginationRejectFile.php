<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 1/23/15
 * Time: 10:05 AM
 */

namespace WellsFargo\ACHBundle\Model;

use WellsFargo\ACHBundle\Model\FileRecordTypes\Type1Record;
use WellsFargo\ACHBundle\Model\FileRecordTypes\Type5Record;
use WellsFargo\ACHBundle\Model\FileRecordTypes\Type6Record;
use WellsFargo\ACHBundle\Model\FileRecordTypes\Type7Record;
use WellsFargo\ACHBundle\Model\FileRecordTypes\Type8Record;
use WellsFargo\ACHBundle\Model\FileRecordTypes\Type9Record;
use WellsFargo\ACHBundle\Model\FileRecordTypes\CompanyBatchRecord;

class NACHAOriginationRejectFile {
    /** @var Type1Record  */
    private $fileHeader = null;

    private $fileControlHeaders = array();

    private $companyBatchRecords = array();

    /** @var null|string */
    private $errorReason = null;

    private $entryDetailRecords = array();


    public function __construct()
    {
    }

    public function parseString($data)
    {
        /** @var CompanyBatchRecord $currentCompanyBatchRecord */
        $currentCompanyBatchRecord = null;

        foreach (preg_split("/((\r?\n)|(\r\n?))/", $data) as $line) {
            $recordType = substr($line, 0, 1);
            switch ($recordType) {
                case '1':
                    $type1Record = new Type1Record();
                    $type1Record->parseLine($line);
                    $this->setFileHeader($type1Record);
                    if ($type1Record->isError()) {
                       $this->setErrorReason($type1Record->getErrorDescription());
                    }
                    break;
                case '5':
                    $type5Record = new Type5Record();
                    $type5Record->parseLine($line);
                    $currentCompanyBatchRecord = new CompanyBatchRecord();
                    $currentCompanyBatchRecord->setCompanyBatchHeader($type5Record);
                    $type5Record->setCompanyBatchRecord($currentCompanyBatchRecord);
                    break;
                case '6':
                    $type6Record = new Type6Record();
                    $type6Record->parseLine($line);
                    $this->entryDetailRecords[] = $type6Record;
                    if (!is_null($currentCompanyBatchRecord)) {
                        $currentCompanyBatchRecord->addEntryDetailRecord($type6Record);
                    }
                    break;
                case '7':
                    $type7Record = new Type7Record();
                    $type7Record->parseLine($line);
                    if (!is_null($currentCompanyBatchRecord)) {
                        $currentCompanyBatchRecord->addAddendaRecord($type7Record);
                    }
                    break;
                case '8':
                    $type8Record = new Type8Record();
                    $type8Record->parseLine($line);
                    if (!is_null($currentCompanyBatchRecord)) {
                        $currentCompanyBatchRecord->setCompanyBatchControl($type8Record);
                        $this->companyBatchRecords[] = $currentCompanyBatchRecord;
                        $currentCompanyBatchRecord = null;
                    }
                    break;
                case '9':
                    $type9Record = new Type9Record();
                    $type9Record->parseLine($line);
                    $this->fileControlHeaders[] = $type9Record;
                    if ($type9Record->isError()) {
                        $this->setErrorReason('Unknown');
                    }
                    break;
                default:
                    break;
            }
        }
    }

    /**
     * Gets the specified entry detail record for an individual id.
     *
     * @param $individualID
     * @return null|Type6Record
     */
    public function getType6RecordWithIndividualID($individualID) {
        /** @var Type6Record $entryDetailRecord */
        foreach ($this->getEntryDetailRecords() as $entryDetailRecord)
        {
            if (strtolower($individualID) == strtolower($entryDetailRecord->getIndividualId())) {
                return $entryDetailRecord;
            }
        }

        return null;
    }


    /**
     * @return Type1Record
     */
    public function getFileHeader()
    {
        return $this->fileHeader;
    }

    /**
     * @param Type1Record $fileHeader
     */
    private function setFileHeader($fileHeader)
    {
        $this->fileHeader = $fileHeader;
    }

    /**
     * @return array
     */
    public function getFileControlHeaders()
    {
        return $this->fileControlHeaders;
    }

    /**
     * @return array
     */
    public function getCompanyBatchRecords()
    {
        return $this->companyBatchRecords;
    }

    /**
     * @return array
     */
    private function getEntryDetailRecords()
    {
        return $this->entryDetailRecords;
    }

    /**
     * @return boolean
     */
    public function isError()
    {
        return !is_null($this->getErrorReason());
    }

    /**
     * @return null|string
     */
    public function getErrorReason()
    {
        return $this->errorReason;
    }

    /**
     * @param null|string $errorReason
     */
    private function setErrorReason($errorReason)
    {
        $this->errorReason = $errorReason;
    }


}