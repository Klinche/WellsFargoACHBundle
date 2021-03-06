<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 2/26/15
 * Time: 11:56 AM
 */

namespace WellsFargo\ACHBundle\Model;

class NACHABatch {

    private $transCode = null;


    private $batchHeader = '';
    public $validBatchHeader = false;
    private $batchLines = '';
    private $batchFooter = '';
    public $validBatchFooter = false;

    public $detailRecordCount = 0;
    public $creditTotal = 0;
    public $debitTotal = 0;
    public $routingHash = 0;

    public $NACHAFile = null;

    public $secType = "";

    private $batchNumber = '1';

    public function __construct(NACHAFile $NACHAFile, $secType, $transCode, $batchNumber) {
        $this->NACHAFile = $NACHAFile;
        $this->secType = $secType;
        $this->batchNumber = $batchNumber;
        $this->transCode = $transCode;
    }

    public function createBatchHeader()
    {
        $companyId = null;

        if ($this->transCode == NachaFile::CHECKING_CREDIT || $this->transCode == NACHAFile::SAVINGS_CREDIT) {
            $companyId = $this->NACHAFile->getCreditCompanyId();
        } else {
            $companyId = $this->NACHAFile->getDebitCompanyId();
        }

        $this->batchHeader = '5'.$this->NACHAFile->getScc().$this->NACHAFile->formatText($this->NACHAFile->getCompanyName(), 16).$this->NACHAFile->formatText($this->NACHAFile->getCompanyDiscretionaryData(), 20).$companyId.$this->secType.$this->NACHAFile->formatText($this->NACHAFile->getCompanyEntryDescription(), 10).$this->NACHAFile->formatText($this->NACHAFile->getCompanyDescriptionDate(), 6).$this->NACHAFile->getEffectiveEntryDate().'   1'.substr($this->NACHAFile->getBankrt(), 0, 8).$this->NACHAFile->formatNumeric($this->batchNumber, 7);
        if (strlen($this->batchHeader) == 94) {
            $this->validBatchHeader = true;
        }

        return $this;
    }

    public function createDetailRecord(NachaPaymentInfo $info)
    {
        $line = '6'.$info->getTransCode().$info->getRoutingNumber().$this->NACHAFile->formatText($info->getBankAccountNumber(), 17).$this->NACHAFile->formatNumeric(number_format($info->getTotalAmount(), 2), 10).$this->NACHAFile->formatText($info->getIndividualId(), 15).$this->NACHAFile->formatText($info->getIndividualName(), 22).$this->NACHAFile->formatText($this->NACHAFile->getDiscretionaryData(), 2).'0'.substr($this->NACHAFile->getBankrt(), 0, 8).$this->NACHAFile->formatNumeric($info->getTranId(), 7);

        if (strlen($line) == 94) {
            $this->batchLines .= $line."\n";
            $this->detailRecordCount++;
            $this->routingHash += (int) substr($info->getRoutingNumber(), 0, 8);
            if ($info->getTransCode() == NACHAFile::CHECKING_DEBIT || $info->getTransCode() == NACHAFile::SAVINGS_DEBIT) {
                $this->debitTotal += (float) $info->getTotalAmount();
            } else {
                $this->creditTotal += (float) $info->getTotalAmount();
            }

            return true;
        }

        return false;
    }

    public function createBatchFooter()
    {
        $companyId = null;
        if ($this->transCode == NachaFile::CHECKING_CREDIT || $this->transCode == NACHAFile::SAVINGS_CREDIT) {
            $companyId = $this->NACHAFile->getCreditCompanyId();
        } else {
            $companyId = $this->NACHAFile->getDebitCompanyId();
        }

        $this->batchFooter = '8'.$this->NACHAFile->getScc().$this->NACHAFile->formatNumeric($this->detailRecordCount, 6).$this->NACHAFile->formatNumeric($this->getRoutingHash(), 10).$this->NACHAFile->formatNumeric(number_format($this->debitTotal, 2), 12).$this->NACHAFile->formatNumeric(number_format($this->creditTotal, 2), 12).$this->NACHAFile->formatText($companyId, 10).$this->NACHAFile->formatText('', 25).substr($this->NACHAFile->getBankrt(), 0, 8).$this->NACHAFile->formatNumeric($this->batchNumber, 7);
        if (strlen($this->batchFooter) == 94) {
            $this->validBatchFooter = true;
        }

        return $this;
    }

    public function getBatchLines() {
        return $this->batchLines;
    }

    public function getBatchHeader() {
        return $this->batchHeader;
    }

    public function getBatchFooter() {
        return $this->batchFooter;
    }

    /**
     * @return int
     */
    public function getDetailRecordCount()
    {
        return $this->detailRecordCount;
    }


    /**
     * @return int
     */
    public function getCreditTotal()
    {
        return $this->creditTotal;
    }


    /**
     * @return int
     */
    public function getDebitTotal()
    {
        return $this->debitTotal;
    }

    /**
     * @return int
     */
    public function getRoutingHash()
    {
        return $this->routingHash;
    }

    /**
     * @return string
     */
    public function getBatchNumber()
    {
        return $this->batchNumber;
    }

}