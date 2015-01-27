<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 1/7/15
 * Time: 3:36 PM
 */

namespace WellsFargo\ACHBundle\Model\FileRecordTypes;

class Type8Record
{
    /** @var string|null */
    private $serviceClassCode = null;

    /** @var string|null */
    private $entryAddendaCount = null;

    /** @var string|null */
    private $entryHash = null;

    /** @var string|null */
    private $totalBatchDebitEntryDollarAmount = null;

    /** @var string|null */
    private $totalBatchCreditEntryDollarAmount = null;

    /** @var string|null */
    private $companyId = null;

    /** @var string|null */
    private $messageAuthenticationCode = null;

    /** @var string|null */
    private $blank = null;

    /** @var string|null */
    private $wellsFargoRoutingNumber = null;

    /** @var string|null */
    private $batchNumber = null;

    /** @var null|string  */
    private $errorCode = null;

    private $errorCodes = array(
        '8500' => 'Service class code invalid',
        '8501' => 'Service class code not equal to 5 record service class code',
        '8502' => 'Service class code not numeric',
        '8503' => 'Entry/addenda count not equal to actual accumulated',
        '8504' => 'Entry/addenda count not numeric',
        '8505' => 'Entry hash total not equal to accumulated 6 record totals of routing/transit numbers',
        '8506' => 'Entry hash totals not numeric',
        '8507' => 'Total debit entry dollar amount not equal to 6 record accumulation',
        '8508' => 'Total debit entry dollar amount not numeric',
        '8509' => 'Total credit entry dollar amount not equal to 6 record accumulation',
        '8510' => 'Total credit entry dollar amount not numeric',
        '8511' => 'Company ID not equal to 5 record company ID',
        '8512' => 'Batch number not equal to 5 record batch number',
        '8513' => 'Batch number not numeric',
        '8514' => 'Batch amount exceeds company level processing limit',
        '8515' => 'Debit only class contained credits; overridden to 200',
        '8516' => 'Credit only class contained debits; overridden to 200',
        '8530' => 'Batch did not contain an 8 record'
    );

    public function __construct()
    {
    }

    public function parseLine($line)
    {
        $this->setServiceClassCode(substr($line, 1, 3));
        $this->setEntryAddendaCount(substr($line, 4, 6));
        $this->setEntryHash(substr($line, 10, 10));
        $this->setTotalBatchDebitEntryDollarAmount($line, 20, 12);
        $this->setTotalBatchCreditEntryDollarAmount(substr($line, 32, 12));
        $this->setCompanyId(substr($line, 44, 10));
        $this->setMessageAuthenticationCode(substr($line, 54, 19));
        $this->setBlank(substr($line, 73, 6));
        $this->setWellsFargoRoutingNumber(substr($line, 79, 8));
        $this->setBatchNumber(substr($line, 87, 7));

        if(substr($line, 79, 4) == 'REJ0') {
            $this->setErrorCode(substr($line, 83, 4));
        }
    }

    /**
     * @return null|string
     */
    public function getServiceClassCode()
    {
        return $this->serviceClassCode;
    }

    /**
     * @param null|string $serviceClassCode
     */
    private function setServiceClassCode($serviceClassCode)
    {
        $this->serviceClassCode = $serviceClassCode;
    }

    /**
     * @return null|string
     */
    public function getEntryAddendaCount()
    {
        return $this->entryAddendaCount;
    }

    /**
     * @param null|string $entryAddendaCount
     */
    private function setEntryAddendaCount($entryAddendaCount)
    {
        $this->entryAddendaCount = $entryAddendaCount;
    }

    /**
     * @return null|string
     */
    public function getEntryHash()
    {
        return $this->entryHash;
    }

    /**
     * @param null|string $entryHash
     */
    private function setEntryHash($entryHash)
    {
        $this->entryHash = $entryHash;
    }

    /**
     * @return null|string
     */
    public function getTotalBatchDebitEntryDollarAmount()
    {
        return $this->totalBatchDebitEntryDollarAmount;
    }

    /**
     * @param null|string $totalBatchDebitEntryDollarAmount
     */
    private function setTotalBatchDebitEntryDollarAmount($totalBatchDebitEntryDollarAmount)
    {
        $this->totalBatchDebitEntryDollarAmount = $totalBatchDebitEntryDollarAmount;
    }

    /**
     * @return null|string
     */
    public function getTotalBatchCreditEntryDollarAmount()
    {
        return $this->totalBatchCreditEntryDollarAmount;
    }

    /**
     * @param null|string $totalBatchCreditEntryDollarAmount
     */
    private function setTotalBatchCreditEntryDollarAmount($totalBatchCreditEntryDollarAmount)
    {
        $this->totalBatchCreditEntryDollarAmount = $totalBatchCreditEntryDollarAmount;
    }

    /**
     * @return null|string
     */
    public function getCompanyId()
    {
        return $this->companyId;
    }

    /**
     * @param null|string $companyId
     */
    private function setCompanyId($companyId)
    {
        $this->companyId = $companyId;
    }

    /**
     * @return null|string
     */
    public function getMessageAuthenticationCode()
    {
        return $this->messageAuthenticationCode;
    }

    /**
     * @param null|string $messageAuthenticationCode
     */
    private function setMessageAuthenticationCode($messageAuthenticationCode)
    {
        $this->messageAuthenticationCode = $messageAuthenticationCode;
    }

    /**
     * @return null|string
     */
    public function getBlank()
    {
        return $this->blank;
    }

    /**
     * @param null|string $blank
     */
    private function setBlank($blank)
    {
        $this->blank = $blank;
    }

    /**
     * @return null|string
     */
    public function getWellsFargoRoutingNumber()
    {
        return $this->wellsFargoRoutingNumber;
    }

    /**
     * @param null|string $wellsFargoRoutingNumber
     */
    private function setWellsFargoRoutingNumber($wellsFargoRoutingNumber)
    {
        $this->wellsFargoRoutingNumber = $wellsFargoRoutingNumber;
    }

    /**
     * @return null|string
     */
    public function getBatchNumber()
    {
        return $this->batchNumber;
    }

    /**
     * @param null|string $batchNumber
     */
    private function setBatchNumber($batchNumber)
    {
        $this->batchNumber = $batchNumber;
    }

    /**
     * @return null|string
     */
    public function getErrorCode()
    {
        return $this->errorCode;
    }

    /**
     * @param null|string $errorCode
     */
    private function setErrorCode($errorCode)
    {
        $this->errorCode = $errorCode;
    }

    /**
     * @return null|string
     */
    public function getErrorDescription() {
        if(is_null($this->getErrorCode())) {
            return null;
        }

        if(!array_key_exists($this->getErrorCode(), $this->errorCodes)) {
            return 'Unknown Error';
        }

        return $this->errorCodes[$this->getErrorCode()];
    }

    /**
     * @return boolean
     */
    public function isError()
    {
        return !is_null($this->getErrorCode());
    }
}
