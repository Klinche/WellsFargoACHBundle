<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 1/23/15
 * Time: 9:52 AM
 */

namespace WellsFargo\ACHBundle\Service;

use JMS\DiExtraBundle\Annotation as DI;
use WellsFargo\ACHBundle\Model\NachaFile;

/**
 * @DI\Service("wellsfargo.achbundle.nachamanager")
 */
class NachaManager {

    /** @var  string  */
    private $bankrt;

    /** @var  string  */
    private $companyId;

    /** @var  string  */
    private $applicationId;

    /** @var  string  */
    private $fileId;

    /** @var  string  */
    private $originatingBank;

    /** @var  string  */
    private $companyName;

    /**
     * @DI\InjectParams({
     *     "routingNumber"                   = @DI\Inject("%wellsfargo.routing_number%"),
     *     "companyId"                       = @DI\Inject("%wellsfargo.company_id%"),
     *     "applicationId"                   = @DI\Inject("%wellsfargo.application_id%"),
     *     "fileId"                          = @DI\Inject("%wellsfargo.file_id%"),
     *     "originatingBank"                 = @DI\Inject("%wellsfargo.originating_bank%"),
     *     "companyName"                     = @DI\Inject("%wellsfargo.company_name%"),
     * })
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
     * Generates a fresh nacha file to use.
     *
     * @return NachaFile
     */
    public function createNachaFile() {
        return new NachaFile($this->bankrt, $this->companyId, $this->applicationId, $this->fileId, $this->originatingBank, $this->companyName);
    }

}