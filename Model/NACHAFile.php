<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 1/23/15
 * Time: 9:54 AM
 */

namespace WellsFargo\ACHBundle\Model;

class NACHAFile {

    const SAVINGS = 'SAVINGS';
    const CHECKING = 'CHECKING';

    const CHECKING_CREDIT = 22;
    const CHECKING_PRENOTE_CREDIT = 23;
    const CHECKING_DEBIT = 27;
    const CHECKING_PRENOTE_DEBIT = 28;

    const SAVINGS_CREDIT = 32;
    const SAVINGS_PRENOTE_CREDIT = 33;
    const SAVINGS_DEBIT = 37;
    const SAVINGS_PRENOTE_DEBIT = 38;

    const SERVICE_CLASS_CODE_200 = '200';
    const SERVICE_CLASS_CODE_220 = '220';
    const SERVICE_CLASS_CODE_225 = '225';

    const SEC_PPD = 'PPD';
    const SEC_CCD = 'CCD';
    const SEC_CTX = 'CTX';

    private $fileId;
    private $companyId;

    public $errorRecords = array();
    public $processedRecords = array();
    private $tranid = 0;
    private $bankrt;
    private $filemodifier = 'A';
    private $originatingBank;
    private $companyName;


    private $scc = self::SERVICE_CLASS_CODE_200;
    private $sec = self::SEC_PPD;
    private $companyEntryDescription = 'PAYMENT';
    private $companyDescriptionDate;
    private $effectiveEntryDate;
    private $fileHeader = '';
    public $validFileHeader = false;
    private $fileFooter = '';
    public $validFileFooter = false;
    private $recordsize = '094';
    private $blockingfactor = '10';
    private $formatcode = '1';
    private $referencecode = '        ';
    public $fileContents = '';
    public $discretionaryData = '';
    private $applicationId = '';
    private $timezone = 'PST8PDT';

    private $companyDiscretionaryData = '';

    private $batchItems = array();



    /**
     */
    public function __construct($routingNumber, $companyId, $applicationId, $fileId, $originatingBank, $companyName)
    {
        $this->bankrt = $routingNumber;
        $this->companyId = $companyId;
        $this->applicationId = $applicationId;
        $this->fileId = $fileId;
        $this->originatingBank = $originatingBank;
        $this->companyName = $companyName;
    }

    /**
     * Generates the nacha file contents
     *
     * @param bool $fileModifier
     * @return string
     * @throws \Exception
     */
    public function generateFileContents($fileModifier = false)
    {
        if ($fileModifier) {
            $this->setFileModifier($fileModifier);
        }
        $this->createFileHeader();

        $batchLines = "";

        /** @var NACHABatch $batch */
        foreach($this->batchItems as $batch) {
            $batch->createBatchHeader();
            $batch->createBatchFooter();

            if(!$batch->validBatchFooter || !$batch->validBatchHeader) {
                throw new \Exception("Invalid batch section Header:   ".$batch->getBatchHeader()." Footer:   ".$batch->getBatchFooter());

            }
            $batchLines = $batchLines."\n".$batch->getBatchHeader()."\n".$batch->getBatchLines().$batch->getBatchFooter();
        }

        $this->createFileFooter();
        if (!$this->validFileHeader) {
            throw new \Exception('Invalid File Header: '.$this->fileHeader);
        }
        if (!$this->validFileFooter) {
            throw new \Exception('Invalid File Footer: '.$this->fileFooter);
        }
        return $this->fileHeader.$batchLines."\n".$this->fileFooter;
    }

    /**
     * Takes money from someone else's account and transfers it into ours.
     *
     * @param  NachaPaymentInfo $paymentInfo
     * @return bool
     */
    public function addDebit(NachaPaymentInfo $paymentInfo, $secType = self::SEC_PPD)
    {
        if (is_null($paymentInfo)) {
            return false;
        }

        if (is_null($paymentInfo->getTransCode())) {
            if (!is_null($paymentInfo->getAccountType())) {
                if ($paymentInfo->getAccountType() == self::CHECKING) {
                    $paymentInfo->setTransCode(self::CHECKING_DEBIT);
                } elseif ($paymentInfo->getAccountType() == self::SAVINGS) {
                    $paymentInfo->setTransCode(self::SAVINGS_DEBIT);
                } else {
                    return false;
                }
            } else {
                $paymentInfo->setTransCode(self::CHECKING_DEBIT);
            }
        }
        $this->addDetailLine($paymentInfo, $secType);

        return true;
    }

    /**
     * Takes moeny from our account and transfers it into someone elses
     *
     * @param  NachaPaymentInfo $paymentInfo
     * @return bool
     */
    public function addCredit(NachaPaymentInfo $paymentInfo, $secType = self::SEC_PPD)
    {
        if (is_null($paymentInfo)) {
            return false;
        }
        if (is_null($paymentInfo->getTransCode())) {
            if (!is_null($paymentInfo->getAccountType())) {
                if ($paymentInfo->getAccountType() == self::CHECKING) {
                    $paymentInfo->setTransCode(self::CHECKING_CREDIT);
                } elseif ($paymentInfo->getAccountType() == self::SAVINGS) {
                    $paymentInfo->setTransCode(self::SAVINGS_CREDIT);
                } else {
                    return false;
                }
            } else {
                $paymentInfo->setTransCode(self::CHECKING_CREDIT);
            }
        }
        return $this->addDetailLine($paymentInfo, $secType);
    }

    private function addDetailLine(NachaPaymentInfo $paymentInfo, $secType)
    {
        if (is_null($paymentInfo->getIndividualId()) || is_null($paymentInfo->getTotalAmount()) || is_null($paymentInfo->getBankAccountNumber()) || is_null($paymentInfo->getRoutingNumber()) || is_null($paymentInfo->getIndividualName()) || is_null($paymentInfo->getAccountType())) {
            return false;
        }
        $paymentInfo->setTranId($this->tranid+1);


        if(!isset($this->batchItems[$secType])) {
            $this->batchItems[$secType] = new NACHABatch($this, $secType, count($this->batchItems) + 1);
        }

        $batchFile = $this->batchItems[$secType];

        if ($batchFile->createDetailRecord($paymentInfo, $secType)) {
            array_push($this->processedRecords, $paymentInfo);
            $this->tranid++;

            return true;
        } else {
            $paymentInfo->setTranId(false);
            array_push($this->errorRecords, $paymentInfo);

            return false;
        }
    }


    private function createFileHeader()
    {
        $this->fileHeader = '101 '.$this->bankrt.$this->fileId.$this->specialDate('ymdHi').$this->filemodifier.$this->recordsize.$this->blockingfactor.$this->formatcode.$this->formatText($this->originatingBank, 23).$this->formatText($this->companyName, 23).$this->formatText($this->referencecode, 8);
        if (strlen($this->fileHeader) >=86  && strlen($this->fileHeader) <= 94) {
            $this->validFileHeader = true;
        }

        return $this;
    }



    private function createFileFooter()
    {
        $linecount = 2;
        $detailRecordCount = 0;
        $debitTotal = 0;
        $creditTotal = 0;
        $routingHashTotal = 0;

        /** @var NACHABatch $batch */
        foreach($this->batchItems as $batch) {
            $linecount = $linecount + 2 + $batch->getDetailRecordCount();
            $detailRecordCount = $detailRecordCount + $batch->getDetailRecordCount();
            $debitTotal = $debitTotal + $batch->getDebitTotal();
            $creditTotal = $creditTotal + $batch->getCreditTotal();
            $routingHashTotal = $routingHashTotal + $batch->getRoutingHash();
        }

        $blocks = ceil(($linecount)/10);
        $this->fileFooter = '9'.$this->formatNumeric('1', 6).$this->formatNumeric($blocks, 6).$this->formatNumeric($detailRecordCount, 8).$this->formatNumeric($routingHashTotal, 10).$this->formatNumeric(number_format($debitTotal, 2), 12).$this->formatNumeric(number_format($creditTotal, 2), 12).$this->formatText('', 39);
        if (strlen($this->fileFooter) == 94) {
            $this->validFileFooter = true;
        }
//        // Add any additional '9' lines to get something evenly divisable by 10.
//        $fillersToAdd = ($blocks*10)-$linecount;
//        for ($i = 0; $i<$fillersToAdd; $i++) {
//            $this->fileFooter .= "\n".str_pad('', 94, '9');
//        }

        return $this;
    }

    public function specialDate($format, $timestamp = null)
    {
        $script_tz = date_default_timezone_get();

        date_default_timezone_set($this->timezone);
        if (is_null($timestamp)) {
            $timestamp = time();
        }
        $date = date($format, $timestamp);
        date_default_timezone_set($script_tz);

        return $date;
    }

    public function formatText($txt, $spaces)
    {
        return substr(str_pad(strtoupper($txt), $spaces, ' ', STR_PAD_RIGHT), 0, $spaces);
    }

    public function formatNumeric($nums, $spaces)
    {
        return substr(str_pad(str_replace(array('.', ','), '', (string) $nums), $spaces, '0', STR_PAD_LEFT), ($spaces)*-1);
    }


    #region "Main File"

    public function setFileModifier($fileModifier)
    {
        $this->filemodifier = $fileModifier;

        return $this;
    }

    public function getFileModifier()
    {
        return $this->filemodifier;
    }

    public function setServiceClassCode($scc)
    {
        $this->scc = $scc;

        return $this;
    }

    public function setSECCode($sec)
    {
        $this->sec = $sec;

        return $this;
    }

    public function setCompanyDiscretionaryData($batchinfo)
    {
        $this->companyDiscretionaryData = $batchinfo;

        return $this;
    }

    public function setCompanyEntryDescription($des = false, $date = false)
    {
        if ($des) {
            $this->companyEntryDescription = $des;
        }
        if ($date) {
            $this->companyDescriptionDate = $this->specialDate('M d', strtotime($date));
        }

        return $this;
    }

    /**
     * @param $timezone
     * @return $this
     */
    public function setTimezone($timezone)
    {
        $this->timezone = $timezone;
        return $this;
    }

    public function setEffectiveEntryDate($date)
    {
        $this->effectiveEntryDate = $this->specialDate('ymd', strtotime($date));

        return $this;
    }

    public function setPaymentTypeCode($type)
    {
        $this->discretionaryData = $type;

        return $this;
    }

    #endregion

    #region "Data"

    /**
     * @return string
     */
    public function getCompanyDiscretionaryData()
    {
        return $this->companyDiscretionaryData;
    }

    /**
     * @return mixed
     */
    public function getFileId()
    {
        return $this->fileId;
    }

    /**
     * @return mixed
     */
    public function getCompanyId()
    {
        return $this->companyId;
    }


    /**
     * @return array
     */
    public function getErrorRecords()
    {
        return $this->errorRecords;
    }

    /**
     * @return array
     */
    public function getProcessedRecords()
    {
        return $this->processedRecords;
    }

    /**
     * @return int
     */
    public function getTranid()
    {
        return $this->tranid;
    }

    /**
     * @return mixed
     */
    public function getBankrt()
    {
        return $this->bankrt;
    }

    /**
     * @return mixed
     */
    public function getOriginatingBank()
    {
        return $this->originatingBank;
    }

    /**
     * @return mixed
     */
    public function getCompanyName()
    {
        return $this->companyName;
    }

    /**
     * @return string
     */
    public function getScc()
    {
        return $this->scc;
    }

    /**
     * @return mixed
     */
    public function getSec()
    {
        return $this->sec;
    }

    /**
     * @return string
     */
    public function getCompanyEntryDescription()
    {
        return $this->companyEntryDescription;
    }

    /**
     * @return mixed
     */
    public function getCompanyDescriptionDate()
    {
        return $this->companyDescriptionDate;
    }

    /**
     * @return mixed
     */
    public function getEffectiveEntryDate()
    {
        return $this->effectiveEntryDate;
    }

    /**
     * @return string
     */
    public function getSecurityRecord()
    {
        return $this->securityRecord;
    }

    /**
     * @return boolean
     */
    public function isValidSecurityRecord()
    {
        return $this->validSecurityRecord;
    }

    /**
     * @return string
     */
    public function getFileHeader()
    {
        return $this->fileHeader;
    }

    /**
     * @return boolean
     */
    public function isValidFileHeader()
    {
        return $this->validFileHeader;
    }

    /**
     * @return string
     */
    public function getFileFooter()
    {
        return $this->fileFooter;
    }

    /**
     * @return boolean
     */
    public function isValidFileFooter()
    {
        return $this->validFileFooter;
    }

    /**
     * @return string
     */
    public function getRecordsize()
    {
        return $this->recordsize;
    }

    /**
     * @return string
     */
    public function getBlockingfactor()
    {
        return $this->blockingfactor;
    }

    /**
     * @return string
     */
    public function getFormatcode()
    {
        return $this->formatcode;
    }

    /**
     * @return string
     */
    public function getReferencecode()
    {
        return $this->referencecode;
    }

    /**
     * @return string
     */
    public function getFileContents()
    {
        return $this->fileContents;
    }

    /**
     * @return string
     */
    public function getDiscretionaryData()
    {
        return $this->discretionaryData;
    }

    /**
     * @return string
     */
    public function getApplicationId()
    {
        return $this->applicationId;
    }

    /**
     * @return string
     */
    public function getTimezone()
    {
        return $this->timezone;
    }

    #endregion


}
