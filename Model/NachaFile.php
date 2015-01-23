<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 1/23/15
 * Time: 9:54 AM
 */

namespace WellsFargo\ACHBundle\Model;

class NachaFile {

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
    public $detailRecordCount = 0;
    private $routingHash = 0;
    public $creditTotal = 0;
    public $debitTotal = 0;
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
    private $securityRecord = '';
    public $validSecurityRecord = false;
    private $fileHeader = '';
    public $validFileHeader = false;
    private $batchHeader = '';
    public $validBatchHeader = false;
    private $companyDiscretionaryData = '';
    private $batchLines = '';
    private $batchNumber = '1';
    private $batchFooter = '';
    public $validBatchFooter = false;
    private $fileFooter = '';
    public $validFileFooter = false;
    private $recordsize = '094';
    private $blockingfactor = '10';
    private $formatcode = '1';
    private $referencecode = '        ';
    public $fileContents = '';
    public $discretionaryData = '';
    private $applicationId = '';
    private $timezone = 'America/Los_Angeles';

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
        $this->createBatchHeader();
        $this->createBatchFooter();
        $this->createFileFooter();
        if (!$this->validFileHeader) {
            throw new \Exception('Invalid File Header');
        }
        if (!$this->validBatchHeader) {
            throw new \Exception('Invalid Batch Header');
        }
        if (!$this->validBatchFooter) {
            throw new \Exception('Invalid Batch Footer');
        }
        if (!$this->validFileFooter) {
            throw new \Exception('Invalid File Footer');
        }
        return $this->securityRecord."\n".$this->fileHeader."\n".$this->batchHeader."\n".$this->batchLines.$this->batchFooter."\n".$this->fileFooter;
    }

    /**
     * Takes money from someone else's account and transfers it into ours.
     *
     * @param  NachaPaymentInfo $paymentInfo
     * @return bool
     */
    public function addDebit(NachaPaymentInfo $paymentInfo)
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
        $this->addDetailLine($paymentInfo);

        return true;
    }

    /**
     * Takes moeny from our account and transfers it into someone elses
     *
     * @param  NachaPaymentInfo $paymentInfo
     * @return bool
     */
    public function addCredit(NachaPaymentInfo $paymentInfo)
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
        return $this->addDetailLine($paymentInfo);
    }

    private function addDetailLine(NachaPaymentInfo $paymentInfo)
    {
        if (is_null($paymentInfo->getIndividualId()) || is_null($paymentInfo->getTotalAmount()) || is_null($paymentInfo->getBankAccountNumber()) || is_null($paymentInfo->getRoutingNumber()) || is_null($paymentInfo->getIndividualName()) || is_null($paymentInfo->getAccountType())) {
            return false;
        }
        $paymentInfo->setTranId($this->tranid+1);

        if ($this->createDetailRecord($paymentInfo)) {
            array_push($this->processedRecords, $paymentInfo);
            $this->tranid++;

            return true;
        } else {
            $paymentInfo->setTranId(false);
            array_push($this->errorRecords, $paymentInfo);

            return false;
        }
    }

    public function setFileModifier($fileModifier)
    {
        $this->filemodifier = $fileModifier;

        return $this;
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

    private function createFileHeader()
    {
        $this->fileHeader = '101 '.$this->bankrt.$this->fileId.$this->specialDate('ymdHi').$this->filemodifier.$this->recordsize.$this->blockingfactor.$this->formatcode.$this->formatText($this->originatingBank, 23).$this->formatText($this->companyName, 23).$this->formatText($this->referencecode, 8);
        if (strlen($this->fileHeader) >=86  && strlen($this->fileHeader) <= 94) {
            $this->validFileHeader = true;
        }

        return $this;
    }

    private function createBatchHeader()
    {
        $this->batchHeader = '5'.$this->scc.$this->formatText($this->companyName, 16).$this->formatText($this->companyDiscretionaryData, 20).$this->companyId.$this->sec.$this->formatText($this->companyEntryDescription, 10).$this->formatText($this->companyDescriptionDate, 6).$this->effectiveEntryDate.'   1'.substr($this->bankrt, 0, 8).$this->formatNumeric($this->batchNumber, 7);
        if (strlen($this->batchHeader) == 94) {
            $this->validBatchHeader = true;
        }

        return $this;
    }

    private function createDetailRecord(NachaPaymentInfo $info)
    {
        $line = '6'.$info->getTransCode().$info->getRoutingNumber().$this->formatText($info->getBankAccountNumber(), 17).$this->formatNumeric(number_format($info->getTotalAmount(), 2), 10).$this->formatText($info->getIndividualId(), 15).$this->formatText($info->getIndividualName(), 22).$this->formatText($this->discretionaryData, 2).'0'.substr($this->bankrt, 0, 8).$this->formatNumeric($info->getTranId(), 7);

        if (strlen($line) == 94) {
            $this->batchLines .= $line."\n";
            $this->detailRecordCount++;
            $this->routingHash += (int) substr($info->getRoutingNumber(), 0, 8);
            if ($info->getTransCode() == self::CHECKING_DEBIT || $info->getTransCode() == self::SAVINGS_DEBIT) {
                $this->debitTotal += (float) $info->getTotalAmount();
            } else {
                $this->creditTotal += (float) $info->getTotalAmount();
            }

            return true;
        }

        return false;
    }

    private function createBatchFooter()
    {
        $this->batchFooter = '8'.$this->scc.$this->formatNumeric($this->detailRecordCount, 6).$this->formatNumeric($this->routingHash, 10).$this->formatNumeric(number_format($this->debitTotal, 2), 12).$this->formatNumeric(number_format($this->creditTotal, 2), 12).$this->formatText($this->companyId, 10).$this->formatText('', 25).substr($this->bankrt, 0, 8).$this->formatNumeric($this->batchNumber, 7);
        if (strlen($this->batchFooter) == 94) {
            $this->validBatchFooter = true;
        }

        return $this;
    }

    private function createFileFooter()
    {
        $linecount = $this->detailRecordCount+4;
        $blocks = ceil(($linecount)/10);
        $this->fileFooter = '9'.$this->formatNumeric('1', 6).$this->formatNumeric($blocks, 6).$this->formatNumeric($this->detailRecordCount, 8).$this->formatNumeric($this->routingHash, 10).$this->formatNumeric(number_format($this->debitTotal, 2), 12).$this->formatNumeric(number_format($this->creditTotal, 2), 12).$this->formatText('', 39);
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

    private function specialDate($format, $timestamp = null)
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

    private function formatText($txt, $spaces)
    {
        return substr(str_pad(strtoupper($txt), $spaces, ' ', STR_PAD_RIGHT), 0, $spaces);
    }

    private function formatNumeric($nums, $spaces)
    {
        return substr(str_pad(str_replace(array('.', ','), '', (string) $nums), $spaces, '0', STR_PAD_LEFT), ($spaces)*-1);
    }


}