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
        '7002' => 'CCD – addenda type code not 01 or 05',
        '7003' => 'CCD – more than one addenda record follows a 6 record',
        '7004' => 'CCD – entry detail sequence number not equal to last 7 digits of 6 record trace number',
        '7005' => 'CIE – addenda type code not 01 or 05',
        '7018' => 'CTX – addenda type code not 05',
        '7019' => 'CTX – addenda sequence number is non-numeric, overridden',
        '7020' => 'CTX – entry detail sequence number not equal to 6 record trace number (last 7 characters)',
        '7021' => 'CTX – addenda sequence number not is ascending order, overridden',
        '7022' => 'MTE – addenda type not 02',
        '7023' => 'MTE – entry detail sequence number not equal to 6 record trace number (last 7 characters)',
        '7024' => 'POS – entry detail sequence number not equal to 6 record trace number (last 7 characters)',
        '7025' => 'SHR – entry detail sequence number not equal to 6 record trace number (last 7 characters)',
        '7026' => 'PPD – addenda type code 01 (originated file)',
        '7027' => 'PPD – addenda type code not 01 or 05',
        '7028' => 'PPD – more than 1 addenda record follows a 6 record',
        '7029' => 'COR – addenda type code is not 98',
        '7030' => 'COR – change code is spaces or zeros',
        '7031' => 'COR – invalid change code',
        '7032' => 'COR – corrected data is zeros or spaces',
        '7033' => 'COR – original entry trace number is zeros',
        '7034' => 'COR – original entry trace number not numeric',
        '7035' => 'COR – routing/transit number is not numeric',
        '7036' => 'COR – routing/transit number is zeros',
        '7059' => 'Addenda type not 01, 02, 03, 04, 05, 98, 99, or AA',
        '7060' => 'Total number of addenda per detail is greater than 9999',
        '7061' => 'Addenda type code not found',
        '7062' => 'COR – (refused NOC) change code is spaces or zeros',
        '7063' => 'COR – (refused NOC) COR trace sequence number is not numeric',
        '7064' => 'COR – (refused NOC) COR trace sequence number is zeros',
        '7065' => 'C01 – incorrectly formatted DFI account number in corrected data field',
        '7066' => 'C02 – incorrectly formatted routing/transit number in corrected data field',
        '7067' => 'C03 – incorrect routing/transit and account number; invalid corrected data format',
        '7068' => 'C04 – incorrectly formatted individual name in corrected data field',
        '7069' => 'C05 – incorrectly formatted transaction code in corrected data field',
        '7070' => 'C06 – incorrect account number and transaction code in corrected data field',
        '7071' => 'C07 – incorrect routing/transit number, account number, and transaction code – invalid corrected data format',
        '7072' => 'C09 – incorrect individual ID; SEC code must be CIE, MTE, POS, or COR',
        '7073' => 'C09 – incorrect individual ID; invalid format in corrected data field',
        '7080' => 'DNE – addenda does not have required 05 addenda type',
        '7090' => 'CBR/PBR – addenda type code is not 01',
        '7091' => 'CBR/PBR – addenda type code is not 01',
        '7092' => 'CBR/PBR – more than 1 addenda record follows a 6 record',
        '7100' => 'POP – addenda record is not allowed',
        '7110' => 'RCK – addenda record not allowed',
        '7119' => 'IAT – 710 addenda – transaction type code invalid',
        '7120' => 'IAT – 710 addenda – invalid foreign payment amount',
        '7121' => 'IAT – 710 addenda – invalid foreign trace number',
        '7122' => 'IAT – 710 addenda – invalid receiving company name',
        '7130' => 'IAT – 711 addenda – invalid originator name',
        '7131' => 'IAT – 711 addenda - invalid originator address – blank',
        '7132' => 'IAT – 711 addenda – invalid originator address – post office box',
        '7140' => 'IAT – 712 addenda – invalid originator city and state',
        '7141' => 'IAT – 712 addenda – invalid originator country and postal code',
        '7150' => 'IAT – 713 addenda – invalid originating DFI name',
        '7151' => 'IAT – 713 addenda – invalid originating DFI identification number qualifier',
        '7152' => 'IAT – 713 addenda – invalid originating DFI identification',
        '7153' => 'IAT – 713 addenda – invalid originating DFI branch country code',
        '7160' => 'IAT – 714 addenda – invalid receiving DFI name',
        '7161' => 'IAT – 714 addenda – invalid receiving DFI identification number qualifier',
        '7162' => 'IAT – 714 addenda – invalid receiving DFI identification',
        '7163' => 'IAT – 714 addenda – invalid receiving DFI branch country code',
        '7170' => 'IAT – 715 addenda – invalid receiver identification number',
        '7171' => 'IAT – 715 addenda – invalid receiver street address',
        '7180' => 'IAT – 716 addenda – invalid receiver city and state',
        '7181' => 'IAT – 716 addenda – invalid receiver country and postal code',
        '7190' => 'IAT – 717 addenda – invalid payment related info',
        '7191' => 'IAT – 717 addenda – invalid addenda sequence number',
        '7192' => 'IAT – 717 addenda – invalid check serial number',
        '7193' => 'IAT – 717 addenda – invalid check serial number and terminal city, state',
        '7200' => 'IAT – 718 addenda – invalid correspondent bank name',
        '7201' => 'IAT – 718 addenda – invalid correspondent bank ID qualifier',
        '7202' => 'IAT – 718 addenda – invalid correspondent bank ID',
        '7203' => 'IAT – 718 addenda – invalid correspondent bank branch country code',
        '7204' => 'IAT – 718 addenda – invalid addenda sequence number',
        '7210' => 'IAT – 799 addenda – invalid original forward payment amount',
        '7211' => 'IAT – 799 addenda – invalid addenda information',
        '7212' => 'IAT – 799 addenda – invalid original trace',
        '7213' => 'IAT – 799 addenda – invalid original routing transit',
        '7501' => 'CCD – addenda type code 01 on originated file, overridden to 05',
        '7502' => 'CCD – addenda type code 01 on received file',
        '7503' => 'CCD – addenda sequence number is non-numeric or all zeros, overridden',
        '7504' => 'CIE – addenda type code 01, overridden',
        '7505' => 'MTE – transaction description is blank',
        '7506' => 'MTE – record terminal ID code is blank',
        '7507' => 'MTE – transaction serial number is blank',
        '7508' => 'MTE – transaction date is not numeric',
        '7509' => 'MTE – transaction time is not numeric',
        '7510' => 'MTE – record terminal location is blank',
        '7511' => 'MTE – record terminal city is blank',
        '7512' => 'MTE – record terminal state is blank',
        '7513' => 'POS – record terminal ID code is blank',
        '7514' => 'POS – transaction serial number is blank',
        '7515' => 'POS – record terminal location is blank',
        '7516' => 'POS – transaction date is not numeric',
        '7517' => 'POS – record terminal city is blank',
        '7518' => 'POS – record terminal state is blank',
        '7519' => 'SHR – record terminal ID code is blank',
        '7520' => 'SHR – transaction serial number is blank',
        '7521' => 'SHR – record terminal location is blank',
        '7522' => 'SHR – transaction date is not numeric',
        '7523' => 'SHR – record terminal city is blank',
        '7524' => 'SHR – record terminal state is blank',
        '7525' => 'PPD – addenda type code 01 on an originated file',
        '7526' => 'PPD – addenda sequence number is non-numeric or all zeros, overridden',
        '7527' => 'PPD – entry detail sequence number not equal to last 7 digits of 6 record trace number',
        '7528' => 'COR – trace number is not numeric',
        '7529' => 'COR – trace number is zeros',
        '7530' => 'CBR/PBR – addenda sequence number is non-numeric or all zeros, overridden',
        '7540' => 'POP – addenda sequence number is non-numeric or all zeros, overridden',
        '7541' => 'POP – addenda type code 01 on an originated file',
        '7550' => 'RCK – addenda sequence number is non-numeric or all zeros, overridden',
        '7551' => 'RCK – addenda type code is 01 on an originated file',
        '7552' => 'TEL – addenda record is not allowed',
        '7553' => 'WEB – addenda type code is not 05',
        '7554' => 'WEB – addenda type code is not numeric',
        '7555' => 'WEB – addenda sequence number is non-numeric or all zeros',
        '7556' => 'WEB – more than one addenda record follows a 6 record'
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

        if(substr($line, 79, 4) == 'REJ0') {
            $this->setErrorCode(substr($line, 83, 4));
        }
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
    private function setAddendaTypeCode($addendaTypeCode)
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
    private function setPaymentRelatedInformation($paymentRelatedInformation)
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
    private function setAddendaSequenceNumber($addendaSequenceNumber)
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
    private function setEntryDetailSequenceNumber($entryDetailSequenceNumber)
    {
        $this->entryDetailSequenceNumber = $entryDetailSequenceNumber;
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
