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
    public function setPriorityCode($priorityCode)
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
    public function setWellsFargoRoutingNumber($wellsFargoRoutingNumber)
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
    public function setFileId($fileId)
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
    public function setFileCreationDate($fileCreationDate)
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
    public function setFileCreationTime($fileCreationTime)
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
    public function setFileIdModifier($fileIdModifier)
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
    public function setRecordSize($recordSize)
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
    public function setBlockingFactor($blockingFactor)
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
    public function setFormatCode($formatCode)
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
    public function setOriginationBank($originationBank)
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
    public function setCompanyName($companyName)
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
    public function setReferenceCode($referenceCode)
    {
        $this->referenceCode = $referenceCode;
    }
}
