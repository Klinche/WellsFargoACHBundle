<?php
/**
 * Created by PhpStorm.
 * User: daniel
 * Date: 1/7/15
 * Time: 3:36 PM
 */

namespace WellsFargo\ACHBundle\Model\FileRecordTypes;

class Type9Record
{
    /** @var string|null */
    private $batchCount = null;

    /** @var string|null */
    private $blockCount = null;

    /** @var string|null */
    private $entryAddendaRecordCount = null;

    /** @var string|null */
    private $entryHashTotal = null;

    /** @var string|null */
    private $totalFileDebitEntryAmount = null;

    /** @var string|null */
    private $totalFileCreditEntryAmount = null;

    /** @var string|null */
    private $filler = null;

    private $errorCodes = array(
        '9500' => 'Batch count not equal to 8 record accumulated count',
        '9501' => 'Batch count not numeric',
        '9502' => 'Block count not numeric',
        '9503' => 'Entry/addenda count not equal to 8 record count accumulated',
        '9504' => 'Entry/addenda count not numeric',
        '9505' => 'Entry hash total not equal to 8 record hash accumulation',
        '9506' => 'Entry hash total not numeric',
        '9507' => 'Total debit entry dollar amount not equal to 8 record totals',
        '9508' => 'Total debit entry dollar amount not numeric',
        '9509' => 'Total credit entry dollar amount not equal to 8 record totals',
        '9510' => 'Total credit entry dollar amount not numeric',
        '9511' => 'Total number of invalid account numbers is greater than 50%',
        '9512' => 'File amount exceeds file reporting debit or credit limits',
        '9530' => 'File did not contain 9 record'
    );

    public function __construct()
    {
    }

    public function parseLine($line)
    {
        $this->setBatchCount(substr($line, 1, 6));
        $this->setBlockCount(substr($line, 7, 6));
        $this->setEntryAddendaRecordCount(substr($line, 13, 8));
        $this->setEntryHashTotal(substr($line, 21, 10));
        $this->setTotalFileDebitEntryAmount(substr($line, 31, 12));
        $this->setTotalFileCreditEntryAmount(substr($line, 43, 12));
        $this->setFiller(substr($line, 55, 39));
    }

    /**
     * @return null|string
     */
    public function getBatchCount()
    {
        return $this->batchCount;
    }

    /**
     * @param null|string $batchCount
     */
    public function setBatchCount($batchCount)
    {
        $this->batchCount = $batchCount;
    }

    /**
     * @return null|string
     */
    public function getBlockCount()
    {
        return $this->blockCount;
    }

    /**
     * @param null|string $blockCount
     */
    public function setBlockCount($blockCount)
    {
        $this->blockCount = $blockCount;
    }

    /**
     * @return null|string
     */
    public function getEntryAddendaRecordCount()
    {
        return $this->entryAddendaRecordCount;
    }

    /**
     * @param null|string $entryAddendaRecordCount
     */
    public function setEntryAddendaRecordCount($entryAddendaRecordCount)
    {
        $this->entryAddendaRecordCount = $entryAddendaRecordCount;
    }

    /**
     * @return null|string
     */
    public function getEntryHashTotal()
    {
        return $this->entryHashTotal;
    }

    /**
     * @param null|string $entryHashTotal
     */
    public function setEntryHashTotal($entryHashTotal)
    {
        $this->entryHashTotal = $entryHashTotal;
    }

    /**
     * @return null|string
     */
    public function getTotalFileDebitEntryAmount()
    {
        return $this->totalFileDebitEntryAmount;
    }

    /**
     * @param null|string $totalFileDebitEntryAmount
     */
    public function setTotalFileDebitEntryAmount($totalFileDebitEntryAmount)
    {
        $this->totalFileDebitEntryAmount = $totalFileDebitEntryAmount;
    }

    /**
     * @return null|string
     */
    public function getTotalFileCreditEntryAmount()
    {
        return $this->totalFileCreditEntryAmount;
    }

    /**
     * @param null|string $totalFileCreditEntryAmount
     */
    public function setTotalFileCreditEntryAmount($totalFileCreditEntryAmount)
    {
        $this->totalFileCreditEntryAmount = $totalFileCreditEntryAmount;
    }

    /**
     * @return null|string
     */
    public function getFiller()
    {
        return $this->filler;
    }

    /**
     * @param null|string $filler
     */
    public function setFiller($filler)
    {
        $this->filler = $filler;
    }
}
