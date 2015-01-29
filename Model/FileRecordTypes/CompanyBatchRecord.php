<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 1/7/15
 * Time: 3:36 PM
 */

namespace WellsFargo\ACHBundle\Model\FileRecordTypes;

class CompanyBatchRecord
{
    /** @var null|Type5Record */
    private $companyBatchHeader = null;

    /** @var array */
    private $entryDetailRecords = array();

    /** @var array */
    private $addendaRecords = array();

    /** @var null|Type8Record */
    private $companyBatchControl = null;


    public function __construct()
    {
    }

    /**
     * @return Type5Record|null
     */
    public function getCompanyBatchHeader()
    {
        return $this->companyBatchHeader;
    }

    /**
     * @param Type5Record $companyBatchHeader
     */
    public function setCompanyBatchHeader($companyBatchHeader)
    {
        $this->companyBatchHeader = $companyBatchHeader;
        $companyBatchHeader->setCompanyBatchRecord($this);
    }

    /**
     * @return array
     */
    public function getEntryDetailRecords()
    {
        return $this->entryDetailRecords;
    }

    /**
     * @param array $entryDetailRecords
     */
    public function setEntryDetailRecords($entryDetailRecords)
    {
        $this->entryDetailRecords = $entryDetailRecords;
    }

    /**
     * @param Type6Record $entryDetailRecord
     */
    public function addEntryDetailRecord($entryDetailRecord)
    {
        $this->entryDetailRecords[] = $entryDetailRecord;
        $entryDetailRecord->setCompanyBatchRecord($this);
    }

    /**
     * @return array
     */
    public function getAddendaRecords()
    {
        return $this->addendaRecords;
    }

    /**
     * @param array $addendaRecords
     */
    public function setAddendaRecords($addendaRecords)
    {
        $this->addendaRecords = $addendaRecords;
    }

    /**
     * @param Type7Record $addendaRecord
     */
    public function addAddendaRecord($addendaRecord)
    {
        $this->addendaRecords[] = $addendaRecord;
        $addendaRecord->setCompanyBatchRecord($this);
    }

    /**
     * @return Type8Record|null
     */
    public function getCompanyBatchControl()
    {
        return $this->companyBatchControl;
    }

    /**
     * @param Type8Record $companyBatchControl
     */
    public function setCompanyBatchControl($companyBatchControl)
    {
        $this->companyBatchControl = $companyBatchControl;
        $companyBatchControl->setCompanyBatchRecord($this);
    }

    /**
     * Signals if the company batch record has a batch error. Does not indicate individual transaction errors.
     *
     * @return bool
     */
    public function isBatchError() {
        return $this->getCompanyBatchControl()->isError() || $this->getCompanyBatchHeader()->isError();
    }

    /**
     * Returns the error for the company batch record.
     *
     * @return bool
     */
    public function getBatchErrorDescription() {
        if ($this->getCompanyBatchControl()->isError()) {
            return $this->getCompanyBatchControl()->getErrorDescription();
        } else if($this->getCompanyBatchHeader()->isError()) {
            return $this->getCompanyBatchHeader()->getErrorDescription();
        }

        return null;
    }
}
