<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 1/7/15
 * Time: 3:36 PM
 */

namespace WellsFargo\ACHBundle\Model\FileRecordTypes;

class Type5Record
{
    /** @var string|null */
    private $serviceClassCode = null;

    /** @var string|null */
    private $companyName = null;

    /** @var string|null */
    private $companyDiscretionaryData = null;

    /** @var string|null */
    private $companyId = null;

    /** @var string|null */
    private $standardEntryClass = null;

    /** @var string|null */
    private $companyEntryDescription = null;

    /** @var string|null */
    private $companyDescriptiveDate = null;

    /** @var string|null */
    private $effectiveEntryDate = null;

    /** @var string|null */
    private $settlementDate = null;

    /** @var string|null */
    private $originatorStatusCode = null;

    /** @var string|null */
    private $wellsFargoRoutingNumber = null;

    /** @var string|null */
    private $batchNumber = null;

    /** @var null|string  */
    private $errorCode = null;

    private $errorCodes = array(
        '5010' => 'Batch rejected due to edits in pre-edit',
    );

    public function __construct()
    {
    }

    public function parseLine($line)
    {
        $this->setServiceClassCode(substr($line, 1, 3));
        $this->setCompanyName(substr($line, 4, 16));
        $this->setCompanyDiscretionaryData(substr($line, 20, 20));
        $this->setCompanyId(substr($line, 40, 10));
        $this->setStandardEntryClass(substr($line, 50, 3));
        $this->setCompanyEntryDescription(substr($line, 53, 10));
        $this->setCompanyDescriptiveDate(substr($line, 63, 6));
        $this->setEffectiveEntryDate(substr($line, 69, 6));
        $this->setSettlementDate(substr($line, 75, 3));
        $this->setOriginatorStatusCode(substr($line, 78, 1));
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
    public function setServiceClassCode($serviceClassCode)
    {
        $this->serviceClassCode = $serviceClassCode;
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
    public function getCompanyDiscretionaryData()
    {
        return $this->companyDiscretionaryData;
    }

    /**
     * @param null|string $companyDiscretionaryData
     */
    public function setCompanyDiscretionaryData($companyDiscretionaryData)
    {
        $this->companyDiscretionaryData = $companyDiscretionaryData;
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
    public function setCompanyId($companyId)
    {
        $this->companyId = $companyId;
    }

    /**
     * @return null|string
     */
    public function getStandardEntryClass()
    {
        return $this->standardEntryClass;
    }

    /**
     * @param null|string $standardEntryClass
     */
    public function setStandardEntryClass($standardEntryClass)
    {
        $this->standardEntryClass = $standardEntryClass;
    }

    /**
     * @return null|string
     */
    public function getCompanyEntryDescription()
    {
        return $this->companyEntryDescription;
    }

    /**
     * @param null|string $companyEntryDescription
     */
    public function setCompanyEntryDescription($companyEntryDescription)
    {
        $this->companyEntryDescription = $companyEntryDescription;
    }

    /**
     * @return null|string
     */
    public function getCompanyDescriptiveDate()
    {
        return $this->companyDescriptiveDate;
    }

    /**
     * @param null|string $companyDescriptiveDate
     */
    public function setCompanyDescriptiveDate($companyDescriptiveDate)
    {
        $this->companyDescriptiveDate = $companyDescriptiveDate;
    }

    /**
     * @return null|string
     */
    public function getEffectiveEntryDate()
    {
        return $this->effectiveEntryDate;
    }

    /**
     * @param null|string $effectiveEntryDate
     */
    public function setEffectiveEntryDate($effectiveEntryDate)
    {
        $this->effectiveEntryDate = $effectiveEntryDate;
    }

    /**
     * @return null|string
     */
    public function getSettlementDate()
    {
        return $this->settlementDate;
    }

    /**
     * @param null|string $settlementDate
     */
    public function setSettlementDate($settlementDate)
    {
        $this->settlementDate = $settlementDate;
    }

    /**
     * @return null|string
     */
    public function getOriginatorStatusCode()
    {
        return $this->originatorStatusCode;
    }

    /**
     * @param null|string $originatorStatusCode
     */
    public function setOriginatorStatusCode($originatorStatusCode)
    {
        $this->originatorStatusCode = $originatorStatusCode;
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
    public function getBatchNumber()
    {
        return $this->batchNumber;
    }

    /**
     * @param null|string $batchNumber
     */
    public function setBatchNumber($batchNumber)
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
