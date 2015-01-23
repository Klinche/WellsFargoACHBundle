<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 1/7/15
 * Time: 3:36 PM
 */

namespace WellsFargo\ACHBundle\Model\FileRecordTypes;

class Type6Record
{
    /** @var string|null */
    private $transactionCode = null;

    /** @var string|null */
    private $receivingDFIRoutingNumber = null;

    /** @var string|null */
    private $routingNumberCheckDigit = null;

    /** @var string|null */
    private $receivingDFIAccountNumber = null;

    /** @var string|null */
    private $amount = null;

    /** @var string|null */
    private $individualId = null;

    /** @var string|null */
    private $individualName = null;

    /** @var string|null */
    private $discretionaryData = null;

    /** @var string|null */
    private $addendaRecordIndicator = null;

    /** @var string|null */
    private $traceNumber = null;

    /** @var array */
    private $addendaRecords = array();

    public function __construct()
    {
    }

    public function parseLine($line)
    {
        $this->setTransactionCode(substr($line, 1, 2));
        $this->setReceivingDFIRoutingNumber(substr($line, 3, 8));
        $this->setRoutingNumberCheckDigit(substr($line, 11, 1));
        $this->setReceivingDFIAccountNumber(substr($line, 12, 17));
        $this->setAmount(substr($line, 29, 10));
        $this->setIndividualId(substr($line, 39, 15));
        $this->setIndividualName(substr($line, 54, 22));
        $this->setDiscretionaryData(substr($line, 76, 2));
        $this->setAddendaRecordIndicator(substr($line, 78, 1));
        $this->setTraceNumber(substr($line, 79, 15));
    }

    /**
     * @return null|string
     */
    public function getTransactionCode()
    {
        return $this->transactionCode;
    }

    /**
     * @param null|string $transactionCode
     */
    public function setTransactionCode($transactionCode)
    {
        $this->transactionCode = $transactionCode;
    }

    /**
     * @return null|string
     */
    public function getReceivingDFIRoutingNumber()
    {
        return $this->receivingDFIRoutingNumber;
    }

    /**
     * @param null|string $receivingDFIRoutingNumber
     */
    public function setReceivingDFIRoutingNumber($receivingDFIRoutingNumber)
    {
        $this->receivingDFIRoutingNumber = $receivingDFIRoutingNumber;
    }

    /**
     * @return null|string
     */
    public function getRoutingNumberCheckDigit()
    {
        return $this->routingNumberCheckDigit;
    }

    /**
     * @param null|string $routingNumberCheckDigit
     */
    public function setRoutingNumberCheckDigit($routingNumberCheckDigit)
    {
        $this->routingNumberCheckDigit = $routingNumberCheckDigit;
    }

    /**
     * @return null|string
     */
    public function getReceivingDFIAccountNumber()
    {
        return $this->receivingDFIAccountNumber;
    }

    /**
     * @param null|string $receivingDFIAccountNumber
     */
    public function setReceivingDFIAccountNumber($receivingDFIAccountNumber)
    {
        $this->receivingDFIAccountNumber = $receivingDFIAccountNumber;
    }

    /**
     * @return null|string
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param null|string $amount
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    /**
     * @return null|string
     */
    public function getIndividualId()
    {
        return $this->individualId;
    }

    /**
     * @param null|string $individualId
     */
    public function setIndividualId($individualId)
    {
        $this->individualId = $individualId;
    }

    /**
     * @return null|string
     */
    public function getIndividualName()
    {
        return $this->individualName;
    }

    /**
     * @param null|string $individualName
     */
    public function setIndividualName($individualName)
    {
        $this->individualName = $individualName;
    }

    /**
     * @return null|string
     */
    public function getDiscretionaryData()
    {
        return $this->discretionaryData;
    }

    /**
     * @param null|string $discretionaryData
     */
    public function setDiscretionaryData($discretionaryData)
    {
        $this->discretionaryData = $discretionaryData;
    }

    /**
     * @return null|string
     */
    public function getAddendaRecordIndicator()
    {
        return $this->addendaRecordIndicator;
    }

    /**
     * @param null|string $addendaRecordIndicator
     */
    public function setAddendaRecordIndicator($addendaRecordIndicator)
    {
        $this->addendaRecordIndicator = $addendaRecordIndicator;
    }

    /**
     * @return null|string
     */
    public function getTraceNumber()
    {
        return $this->traceNumber;
    }

    /**
     * @param null|string $traceNumber
     */
    public function setTraceNumber($traceNumber)
    {
        $this->traceNumber = $traceNumber;
    }
}
