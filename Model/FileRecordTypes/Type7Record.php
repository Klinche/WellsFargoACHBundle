<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 1/7/15
 * Time: 3:36 PM
 */

namespace WellsFargo\ACHBundle\Model\FileRecordTypes;

class Type7Record
{
    /** @var string|null */
    private $addendaTypeCode = null;

    /** @var string|null */
    private $paymentRelatedInformation = null;

    /** @var string|null */
    private $addendaSequenceNumber = null;

    /** @var string|null */
    private $entryDetailSequenceNumber = null;

    /** @var null|string  */
    private $errorCode = null;

    private $errorCodes = array(
        '7001' => 'CCD – addenda type code 01 on a received file',
    );

    public function __construct()
    {
    }

    public function parseLine($line)
    {
        $this->setAddendaTypeCode(substr($line, 1, 2));
        $this->setPaymentRelatedInformation(substr($line, 3, 80));
        $this->setAddendaSequenceNumber(substr($line, 83, 4));
        $this->setEntryDetailSequenceNumber(substr($line, 87, 7));
    }

    /**
     * @return null|string
     */
    public function getAddendaTypeCode()
    {
        return $this->addendaTypeCode;
    }

    /**
     * @param null|string $addendaTypeCode
     */
    public function setAddendaTypeCode($addendaTypeCode)
    {
        $this->addendaTypeCode = $addendaTypeCode;
    }

    /**
     * @return null|string
     */
    public function getPaymentRelatedInformation()
    {
        return $this->paymentRelatedInformation;
    }

    /**
     * @param null|string $paymentRelatedInformation
     */
    public function setPaymentRelatedInformation($paymentRelatedInformation)
    {
        $this->paymentRelatedInformation = $paymentRelatedInformation;
    }

    /**
     * @return null|string
     */
    public function getAddendaSequenceNumber()
    {
        return $this->addendaSequenceNumber;
    }

    /**
     * @param null|string $addendaSequenceNumber
     */
    public function setAddendaSequenceNumber($addendaSequenceNumber)
    {
        $this->addendaSequenceNumber = $addendaSequenceNumber;
    }

    /**
     * @return null|string
     */
    public function getEntryDetailSequenceNumber()
    {
        return $this->entryDetailSequenceNumber;
    }

    /**
     * @param null|string $entryDetailSequenceNumber
     */
    public function setEntryDetailSequenceNumber($entryDetailSequenceNumber)
    {
        $this->entryDetailSequenceNumber = $entryDetailSequenceNumber;
        if(substr($this->entryDetailSequenceNumber,0, 4) == 'REJ0') {
            $this->setErrorCode(substr($this->entryDetailSequenceNumber, 4, 4));
        }
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
    public function setErrorCode($errorCode)
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
}
