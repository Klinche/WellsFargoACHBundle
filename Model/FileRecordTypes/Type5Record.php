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

    /** @var CompanyBatchRecord $companyBatchRecord */
    private $companyBatchRecord = null;

    /** @var null|string  */
    private $errorCode = null;

    private $errorCodes = array(
        '5010' => 'Batch rejected due to edits in pre-edit',
        '5020' => 'File rejected due to company identification errors',
        '5030' => 'Batch rejected – descriptive date was spaces for a MTE, POS or SHR',
        '5040' => 'Batch rejected – class code of COR sent from originator',
        '5070' => 'Batch contained invalid entry class; forced to PPD',
        '5080' => 'Reject credits – foreign exchange indicator and foreign exchange reference indicator conflict',
        '5082' => 'Reject credits – unknown foreign exchange indicator on CBR or PBR batch',
        '5090' => 'Batch rejected – class code DNE sent from originator',
        '5101' => 'IAT – batch rejected – invalid foreign exchange indicator',
        '5102' => 'IAT – batch rejected – invalid ISO destination country code',
        '5103' => 'IAT – customer with ACF IAT setup, contains invalid class code',
        '5104' => 'IAT – batch rejected – customer not set up on ACF for IAT',
        '5105' => 'IAT – batch rejected – invalid ISO origination currency code',
        '5106' => 'IAT – batch rejected – invalid ISO destination currency code',
        '5107' => 'IAT – batch rejected – IATCOR without class code set to COR',
        '5108' => 'IAT – batch rejected – foreign exchange reference indicator and foreign exchange reference conflict',
        '5510' => 'Batch contained invalid service class code',
        '5512' => 'Service class code did not match customer file',
        '5515' => 'Service class code was not numeric',
        '5522' => 'ACF special rules expiration date expired',
        '5530' => 'Company identification was modified in pre-edit',
        '5540' => 'Originating DFI is not a Wells Fargo originator',
        '5550' => 'Originating DFI was not found',
        '5560' => 'Originating DFI was not numeric',
        '5570' => 'Posting date was changed in pre-edit process',
        '5580' => 'Posting date was in invalid format',
        '5590' => 'Originating status code was not valid',
        '5600' => 'Entry description area was all spaces',
        '5605' => 'Entry description area was not alphanumeric as required by Fed',
        '5606' => 'RCK entry class has invalid entry description',
        '5607' => 'POP entry class has invalid entry description',
        '5608' => 'PPD entry class cannot have REDEPCHECK in entry description',
        '5610' => 'Originating company name was not all alphanumeric',
        '5615' => 'Originating company name was all spaces',
        '5620' => 'Descriptive date was spaces for a MTE, POS, or SHR',
        '5630' => 'Batch file number was not numeric',
        '5650' => 'Batch sequence number was not numeric',
        '5660' => 'Foreign exchange indicator is fixed to fixed but related fields differ',
        '5662' => 'Foreign exchange reference indicator is fixed to fixed but related fields differ',
        '5664' => 'Unknown foreign exchange indicator; changed to fixed to fixed',
        '5670' => 'Foreign exchange reference contains non-alphanumeric data',
        '5675' => 'ISO destination country code contains non-alphanumeric data',
        '5680' => 'ISO originating currency code contains non-alphanumeric data',
        '5685' => 'ISO destination currency code contains non-alphanumeric data',
        '5701' => 'IAT entry class has missing IAT indicator',
        '5702' => 'IAT entry class has missing foreign exchange reference indicator',
        '5703' => 'IAT entry class has missing foreign exchange reference',
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
    private function setServiceClassCode($serviceClassCode)
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
    private function setCompanyName($companyName)
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
    private function setCompanyDiscretionaryData($companyDiscretionaryData)
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
    private function setCompanyId($companyId)
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
    private function setStandardEntryClass($standardEntryClass)
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
    private function setCompanyEntryDescription($companyEntryDescription)
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
    private function setCompanyDescriptiveDate($companyDescriptiveDate)
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
    private function setEffectiveEntryDate($effectiveEntryDate)
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
    private function setSettlementDate($settlementDate)
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
    private function setOriginatorStatusCode($originatorStatusCode)
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
     * @return CompanyBatchRecord
     */
    public function getCompanyBatchRecord()
    {
        return $this->companyBatchRecord;
    }

    /**
     * @param CompanyBatchRecord $companyBatchRecord
     */
    public function setCompanyBatchRecord($companyBatchRecord)
    {
        $this->companyBatchRecord = $companyBatchRecord;
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
