<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 1/7/15
 * Time: 3:36 PM
 */

namespace WellsFargo\ACHBundle\Model\FileRecordTypes;

class Type1Record
{
    /** @var string|null */
    private $priorityCode = null;

    /** @var string|null */
    private $wellsFargoRoutingNumber = null;

    /** @var string|null */
    private $fileId = null;

    /** @var string|null */
    private $fileCreationDate = null;

    /** @var string|null */
    private $fileCreationTime = null;

    /** @var \DateTime|null */
    private $fileCreationDateTime = null;

    /** @var string|null */
    private $fileIdModifier = null;

    /** @var string|null */
    private $recordSize = null;

    /** @var string|null */
    private $blockingFactor = null;

    /** @var string|null */
    private $formatCode = null;

    /** @var string|null */
    private $originationBank = null;

    /** @var string|null */
    private $companyName = null;

    /** @var string|null */
    private $referenceCode = null;

    /** @var null|string  */
    private $errorCode = null;


    private $errorCodes = array(
        '1020' => 'Rejected in pre-edit',
        '1030' => 'File rejected for control errors in pre-edit',
        '1040' => 'File rejected due to control errors',
        '1050' => 'File rejected due to file identification errors',
        '1060' => 'Rejected due to duplicate presence',
        '1070' => 'Reject due to risk',
        '1505' => 'Immediate destination not found',
        '1507' => 'Immediate destination is not numeric',
        '1510' => 'File ID was modified according to customer setup',
        '1512' => 'Special rules expiration date has passed',
        '1520' => 'File ID modifier contained a space',
        '1525' => 'File ID modifier is not alphanumeric as required by Fed',
        '1530' => 'Create date was non-numeric or zero',
        '1540' => 'Create date contained incorrect format',
        '1550' => 'Priority code was not numeric',
        '1560' => 'Immediate destination name was spaces',
        '1561' => 'Immediate origin name is spaces',
        '1570' => 'Record length not 94 bytes (94 characters)',
        '1580' => 'Record length was not numeric',
        '1590' => 'Blocking factor not 10',
        '1600' => 'Blocking factor was not numeric',
        '1610' => 'Format code was not 1',
    );

    public function __construct()
    {
    }

    public function parseLine($line)
    {
        $this->setPriorityCode(substr($line, 1, 2));
        $this->setWellsFargoRoutingNumber(substr($line, 3, 10));
        $this->setFileId(substr($line, 13, 10));
        $this->setFileCreationDate(substr($line, 23, 6));
        $this->setFileCreationTime(substr($line, 29, 4));
        $this->setFileIdModifier(substr($line, 33, 1));
        $this->setRecordSize(substr($line, 34, 3));
        $this->setBlockingFactor(substr($line, 37, 2));
        $this->setFormatCode(substr($line, 39, 1));
        $this->setOriginationBank(substr($line, 40, 23));
        $this->setCompanyName(substr($line, 63, 23));
        $this->setReferenceCode(substr($line, 86, 8));

        if(substr($line, 79, 4) == 'REJ0') {
            $this->setErrorCode(substr($line, 83, 4));
        }

        $this->fileCreationDateTime = \DateTime::createFromFormat('ymd Hi', $this->getFileCreationDate().' '.$this->getFileCreationTime(), new \DateTimeZone('PST8PDT'));
    }


    /**
     * @return null|string
     */
    public function getPriorityCode()
    {
        return $this->priorityCode;
    }

    /**
     * @param null|string $priorityCode
     */
    private function setPriorityCode($priorityCode)
    {
        $this->priorityCode = $priorityCode;
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
    public function getFileId()
    {
        return $this->fileId;
    }

    /**
     * @param null|string $fileId
     */
    private function setFileId($fileId)
    {
        $this->fileId = $fileId;
    }

    /**
     * @return null|string
     */
    public function getFileCreationDate()
    {
        return $this->fileCreationDate;
    }

    /**
     * @param null|string $fileCreationDate
     */
    private function setFileCreationDate($fileCreationDate)
    {
        $this->fileCreationDate = $fileCreationDate;
    }

    /**
     * @return null|string
     */
    public function getFileCreationTime()
    {
        return $this->fileCreationTime;
    }

    /**
     * @param null|string $fileCreationTime
     */
    private function setFileCreationTime($fileCreationTime)
    {
        $this->fileCreationTime = $fileCreationTime;
    }

    /**
     * @return null|string
     */
    public function getFileIdModifier()
    {
        return $this->fileIdModifier;
    }

    /**
     * @param null|string $fileIdModifier
     */
    private function setFileIdModifier($fileIdModifier)
    {
        $this->fileIdModifier = $fileIdModifier;
    }

    /**
     * @return null|string
     */
    public function getRecordSize()
    {
        return $this->recordSize;
    }

    /**
     * @param null|string $recordSize
     */
    private function setRecordSize($recordSize)
    {
        $this->recordSize = $recordSize;
    }

    /**
     * @return null|string
     */
    public function getBlockingFactor()
    {
        return $this->blockingFactor;
    }

    /**
     * @param null|string $blockingFactor
     */
    private function setBlockingFactor($blockingFactor)
    {
        $this->blockingFactor = $blockingFactor;
    }

    /**
     * @return null|string
     */
    public function getFormatCode()
    {
        return $this->formatCode;
    }

    /**
     * @param null|string $formatCode
     */
    private function setFormatCode($formatCode)
    {
        $this->formatCode = $formatCode;
    }

    /**
     * @return null|string
     */
    public function getOriginationBank()
    {
        return $this->originationBank;
    }

    /**
     * @param null|string $originationBank
     */
    private function setOriginationBank($originationBank)
    {
        $this->originationBank = $originationBank;
    }

    /**
     * @return null|string
     */
    public function getCompanyName()
    {
        return $this->companyName;
    }

    /**
     * @param null|string $companyName
     */
    private function setCompanyName($companyName)
    {
        $this->companyName = $companyName;
    }

    /**
     * @return null|string
     */
    public function getReferenceCode()
    {
        return $this->referenceCode;
    }

    /**
     * @param null|string $referenceCode
     */
    private function setReferenceCode($referenceCode)
    {
        $this->referenceCode = $referenceCode;
    }

    /**
     * @return \DateTime|null
     */
    public function getFileCreationDateTime()
    {
        return $this->fileCreationDateTime;
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
