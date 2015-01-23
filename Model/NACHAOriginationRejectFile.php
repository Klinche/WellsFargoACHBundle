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
                    $this->fileHeader = $type1Record;
                    break;
                case '5':
                    $type5Record = new Type5Record();
                    $type5Record->parseLine($line);
                    $currentCompanyBatchRecord = $type5Record;
                    break;
                case '6':
                    $type6Record = new Type6Record();
                    $type6Record->parseLine($line);
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
                    break;
                default:
                    break;
            }
        }
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
    public function setFileHeader($fileHeader)
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
     * @param array $fileControlHeaders
     */
    public function setFileControlHeaders($fileControlHeaders)
    {
        $this->fileControlHeaders = $fileControlHeaders;
    }

    /**
     * @return array
     */
    public function getCompanyBatchRecords()
    {
        return $this->companyBatchRecords;
    }

    /**
     * @param array $companyBatchRecords
     */
    public function setCompanyBatchRecords($companyBatchRecords)
    {
        $this->companyBatchRecords = $companyBatchRecords;
    }
}