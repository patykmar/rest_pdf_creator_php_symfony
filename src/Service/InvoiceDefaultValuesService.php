<?php

namespace App\Service;

use App\Model\DataClass\InvoiceDefaultValues;

class InvoiceDefaultValuesService
{
    public const INVOICE_VS_LENGTH = 6;
    public const DUE_DAY = 14;
    public const CURRENCY = "CZK";
    public const KS = "0308";
    public const PAYMENT_TYPE = "transfer";
    private InvoiceDefaultValues $invoiceDefaultValues;

    public function __construct()
    {
        $this->invoiceDefaultValues = new InvoiceDefaultValues(
            self::INVOICE_VS_LENGTH,
            self::DUE_DAY,
            self::CURRENCY,
            self::KS,
            self::PAYMENT_TYPE
        );
    }

    /**
     * @return InvoiceDefaultValues
     */
    public function getInvoiceDefaultValues(): InvoiceDefaultValues
    {
        return $this->invoiceDefaultValues;
    }

}
