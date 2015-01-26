<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 1/23/15
 * Time: 9:56 AM
 */

namespace WellsFargo\ACHBundle\Model;


class NACHAPaymentInfo {
    /** @var string  */
    private $transCode = null;

    /** @var string  */
    private $accountType = null;

    /** @var string  */
    private $individualId = null;

    /** @var string  */
    private $totalAmount = null;

    /** @var string  */
    private $bankAccountNumber = null;

    /** @var string  */
    private $routingNumber = null;

    /** @var string  */
    private $individualName = null;

    /** @var string  */
    private $tranId = null;

    /**
     * @return string
     */
    public function getIndividualId()
    {
        return $this->individualId;
    }

    /**
     * @param string $accountNumber
     */
    public function setIndividualId($accountNumber)
    {
        $this->individualId = $accountNumber;
    }

    /**
     * @return string
     */
    public function getAccountType()
    {
        return $this->accountType;
    }

    /**
     * @param string $accountType
     */
    public function setAccountType($accountType)
    {
        $this->accountType = $accountType;
    }

    /**
     * @return string
     */
    public function getBankAccountNumber()
    {
        return $this->bankAccountNumber;
    }

    /**
     * @param string $bankAccountNumber
     */
    public function setBankAccountNumber($bankAccountNumber)
    {
        $this->bankAccountNumber = $bankAccountNumber;
    }

    /**
     * @return string
     */
    public function getIndividualName()
    {
        return $this->individualName;
    }

    /**
     * @param string $formattedName
     */
    public function setIndividualName($formattedName)
    {
        $this->individualName = $formattedName;
    }

    /**
     * @return string
     */
    public function getRoutingNumber()
    {
        return $this->routingNumber;
    }

    /**
     * @param string $routingNumber
     */
    public function setRoutingNumber($routingNumber)
    {
        $this->routingNumber = $routingNumber;
    }

    /**
     * @return string
     */
    public function getTotalAmount()
    {
        return $this->totalAmount;
    }

    /**
     * @param string $totalAmount
     */
    public function setTotalAmount($totalAmount)
    {
        $this->totalAmount = $totalAmount;
    }

    /**
     * @return string
     */
    public function getTranId()
    {
        return $this->tranId;
    }

    /**
     * @param string $tranId
     */
    public function setTranId($tranId)
    {
        $this->tranId = $tranId;
    }

    /**
     * @return string
     */
    public function getTransCode()
    {
        return $this->transCode;
    }

    /**
     * @param string $transCode
     */
    public function setTransCode($transCode)
    {
        $this->transCode = $transCode;
    }
}