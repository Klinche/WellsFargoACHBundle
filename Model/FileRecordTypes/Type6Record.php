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

    /** @var null|string  */
    private $errorCode = null;

    private $errorCodes = array(
        '6001' => 'Receiving DFI ID not found',
        '6002' => 'Addenda record indicator is non-numeric',
        '6003' => 'Addenda record indicator not 1 or 0 on a received file',
        '6004' => 'Addenda record indicator is 1, but addenda record does not follow',
        '6005' => 'Transaction code is invalid – changed to a demand credit (22), same as previous detail record',
        '6006' => 'Transaction code is invalid – changed to a demand debit (27), same as previous detail record',
        '6007' => 'Amount is not zero for a prenote',
        '6008' => 'Amount is zero for a non-prenote',
        '6009' => 'Amount field is non-numeric',
        '6010' => 'Invalid characters found in account number field',
        '6011' => 'CCD – transaction code is a return type',
        '6012' => 'Individual ID number is spaces or zeros',
        '6014' => 'CTX – number of special addenda not equal to accumulated addenda records',
        '6015' => 'MTE – addenda record indicator is not 1',
        '6016' => 'MTE – individual name is spaces',
        '6017' => 'MTE – individual ID number is spaces or zeros',
        '6018' => 'MTE – record after 6 record is not a 7 record',
        '6019' => 'Item rejected – card type transaction code is zeros for POS class',
        '6020' => 'Item rejected – card type transaction code was not alphameric for POS class',
        '6021' => 'POS – record after 6 record is not a 7 record',
        '6022' => 'SHR – item rejected, card type transaction code was not numeric',
        '6023' => 'SHR – record after 6 record is not a 7 record',
        '6024' => 'PPD – transaction code cannot be a return',
        '6025' => 'COR – transaction code not a return',
        '6026' => 'COR – dollar amount is not numeric',
        '6027' => 'COR – dollar amount is greater than zero',
        '6029' => 'MICR item amount is not greater than zero',
        '6030' => 'Receiving DFI ID not within range',
        '6031' => 'Invalid transaction code – changed to a savings credit (32), same as previous detail record',
        '6032' => 'Invalid transaction code – changed to a savings debit (37), same as previous detail record',
        '6033' => 'Invalid transaction code – forced to (27) because transaction code for previous record not known',
        '6034' => 'Individual ID number cannot be spaces or zeros for CIE',
        '6035' => 'Invalid character(s) found in individual ID field',
        '6036' => 'Invalid character(s) found in individual name field',
        '6037' => 'Invalid characters found in discretionary data field (CCD, PPD, CIE, MTE)',
        '6038' => 'Invalid discretionary data value',
        '6039' => 'Invalid characters found in individual name',
        '6040' => 'Invalid characters found in sending company audit field (CTX)',
        '6041' => 'Invalid characters found in receiving company name/ID (CTX)',
        '6042' => 'Invalid characters found in discretionary data field (CTX)',
        '6043' => 'Invalid characters found in card expiration date',
        '6044' => 'Invalid characters found in document reference number',
        '6045' => 'SHR – card type transaction code was not numeric',
        '6046' => 'Original trace sequence number is not numeric or is zero',
        '6047' => 'Original trace sequence number not in ascending order',
        '6050' => 'Created prenote was rejected because related item reject',
        '6051' => 'DNE transaction is not in prenote format',
        '6052' => 'DNE item does not contain an addenda',
        '6053' => 'DNE items can have only one addenda',
        '6054' => 'Receiving DFI not found',
        '6055' => 'Invalid character in receiving DFI',
        '6056' => 'Transaction code not numeric',
        '6057' => 'Transaction code not valid',
        '6058' => 'GL/Recon Plus transaction code not valid',
        '6059' => 'Remittance transaction contains non-zero dollar amount',
        '6060' => 'Remittance transaction cannot be used with current class code',
        '6061' => 'Transaction code must be credit remittance transaction',
        '6062' => 'Transaction requires an addenda',
        '6063' => 'Transaction requires “REVERSAL” in the entry description on the 5 record',
        '6064' => 'G/L transaction account missing source code preceded by an asterisk (*)',
        '6065' => 'G/L transactions require at least 9 digits prior to the asterisk (*) and source',
        '6066' => 'G/L transaction was classified as MICR; cannot process G/L transaction',
        '6070' => 'CBR/PBR – record after 6 record is not a 7 record',
        '6071' => 'CBR/PBR – transaction code cannot be a return',
        '6072' => 'Serial number must be supplied',
        '6073' => 'Credit rejected – batch 5 record has invalid data in international data fields',
        '6080' => 'POP – transaction code cannot be a return transaction code',
        '6081' => 'POP – invalid serial number/city/state',
        '6090' => 'Transaction code cannot be a return transaction code',
        '6091' => 'Invalid or blank entry description',
        '6092' => 'RCK – transaction exceeds maximum dollar amount',
        '6093' => 'WEB/TEL – transaction code cannot be a return',
        '6094' => 'WEB/TEL – invalid credit transaction',
        '6100' => 'Check conversion not allowed for this routing/transit number',
        '6101' => 'Check conversion – account number exceeds allowed length for this routing/transit number',
        '6102' => 'Check conversion – check serial number field exceeds maximum of five digits',
        '6103' => 'ARC/BOC/POP transactions over $25,000',
        '6104' => 'IAT – invalid or missing number of addenda records',
        '6105' => 'IAT – missing all mandatory addenda records for origination',
        '6106' => 'IAT – missing all mandatory addenda records for a return',
        '6107' => 'IAT – invalid account number',
        '6108' => 'IAT – missing required remittance (717) addenda on ARC, BOC, RCK or POP',
        '6109' => 'IAT – RTN must be 391001268 for international transactions',
        '6110' => 'IAT – number of addenda records field is non-numeric',
        '6111' => 'IAT – number of addenda records field does not match number of addenda records with item',
        '6112' => 'IAT – transaction contains no addenda records',
        '6113' => 'IAT – transaction contains more than two 717 type addenda records',
        '6114' => 'IAT – transaction contains more than a total of five 717 and 718 addenda',
        '6501' => 'Addenda indicator not 1 or 0',
        '6502' => 'Addenda indicator not 1 – overridden on originated file',
        '6503' => 'Trace number not numeric – override with bank control routing/transit number for origination file',
        '6504' => 'Trace number does not equal routing/transit number on 5 record',
        '6505' => 'Check digit is not numeric on originated file',
        '6506' => 'Check digit invalid on originated file',
        '6507' => 'File level item limit exceeded',
        '6508' => 'Company level item limit exceeded',
        '6509' => 'Transaction code is invalid for service class code of 200',
        '6510' => 'Transaction code is invalid for service class code of 225',
        '6511' => 'Receiving company name/ID number is not left-justified',
        '6512' => 'CTX – receiving company name/ID number is not left-justified',
        '6513' => 'POS – addenda record indicator not 1',
        '6514' => 'SHR – addenda record indicator not 1',
        '6515' => 'Addenda indicator not 0 – overridden on originated file',
        '6516' => 'Addenda record indicator must be 1 for MTE class code',
        '6517' => 'Invalid account number – contains only spaces, slashes, zeros, or dashes',
        '6518' => 'Account number is not left-justified',
        '6520' => 'Transaction doesn’t qualify as zero dollar transaction – prenote forced',
        '6530' => 'Account number on both AA addenda and detail record',
        '6531' => 'Detail account number existed and AA addenda existed without account number',
        '6532' => 'Discretionary data of 01 and AA addenda existed but no account number found',
        '6533' => 'Discretionary data of 01, account number missing from detail; no AA addenda',
        '6534' => 'Discretionary data of 01, account number missing from detail; no addenda record',
        '6535' => 'Discretionary data 01 forced because of AA addenda',
        '6536' => 'Discretionary data 01 forced because of 02 addenda',
        '6537' => 'WEB – Position 77 must be R or S',
        '6538' => 'Found R or S in position 78, moved to position 77',
        '6551' => 'IAT – invalid OFAC screening indicator',
        '6552' => 'IAT – invalid secondary OFAC screening indicator',
        '6553' => 'IAT – entry detail record – reserved area has data in position 17-29'
    );

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

        if(substr($line, 79, 4) == 'REJ0') {
            $this->setErrorCode(substr($line, 83, 4));
        }
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
