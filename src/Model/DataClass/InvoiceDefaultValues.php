<?php

namespace App\Model\DataClass;


class InvoiceDefaultValues
{
    /**
     * @param int $invoiceVsLength
     * @param int $dueDay
     * @param string $currencyCode
     * @param string $ks
     */
    public function __construct(
        private readonly int    $invoiceVsLength,
        private readonly int    $dueDay,
        private readonly string $currencyCode,
        private readonly string $ks
    )
    {
    }

    /**
     * @return int
     */
    public function getInvoiceVsLength(): int
    {
        return $this->invoiceVsLength;
    }

    /**
     * @return int
     */
    public function getDueDay(): int
    {
        return $this->dueDay;
    }

    /**
     * @return string
     */
    public function getKs(): string
    {
        return $this->ks;
    }

    /**
     * @return string
     */
    public function getCurrencyCode(): string
    {
        return $this->currencyCode;
    }

}
