<?php

namespace App\Model\Dto;

final class InvoiceDto
{
    private CompanyDto $supplier;
    private CompanyDto $subscriber;
    private string $paymentType;
    private ?int $created = null;
    private ?int $dueDay = null;
    private ?string $vs = null;
    private ?string $ks = null;
    private ?string $currency = null;

    /**
     * @var InvoiceItemDto[]
     */
    private array $invoiceItems;

    /**
     * @return CompanyDto
     */
    public function getSupplier(): CompanyDto
    {
        return $this->supplier;
    }

    /**
     * @return CompanyDto
     */
    public function getSubscriber(): CompanyDto
    {
        return $this->subscriber;
    }

    /**
     * @return string
     */
    public function getPaymentType(): string
    {
        return $this->paymentType;
    }

    /**
     * @return int|null
     */
    public function getCreated(): ?int
    {
        return $this->created;
    }

    /**
     * @return int|null
     */
    public function getDueDay(): ?int
    {
        return $this->dueDay;
    }

    /**
     * @return string|null
     */
    public function getVs(): ?string
    {
        return $this->vs;
    }

    /**
     * @return string|null
     */
    public function getKs(): ?string
    {
        return $this->ks;
    }

    /**
     * @return string|null
     */
    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    /**
     * @param CompanyDto $supplier
     * @return InvoiceDto
     */
    public function setSupplier(CompanyDto $supplier): InvoiceDto
    {
        $this->supplier = $supplier;
        return $this;
    }

    /**
     * @param CompanyDto $subscriber
     * @return InvoiceDto
     */
    public function setSubscriber(CompanyDto $subscriber): InvoiceDto
    {
        $this->subscriber = $subscriber;
        return $this;
    }

    /**
     * @param string $paymentType
     * @return InvoiceDto
     */
    public function setPaymentType(string $paymentType): InvoiceDto
    {
        $this->paymentType = $paymentType;
        return $this;
    }

    /**
     * @param int|null $created
     * @return InvoiceDto
     */
    public function setCreated(?int $created): InvoiceDto
    {
        $this->created = $created;
        return $this;
    }

    /**
     * @param int|null $dueDay
     * @return InvoiceDto
     */
    public function setDueDay(?int $dueDay): InvoiceDto
    {
        $this->dueDay = $dueDay;
        return $this;
    }

    /**
     * @param string|null $vs
     * @return InvoiceDto
     */
    public function setVs(?string $vs): InvoiceDto
    {
        $this->vs = $vs;
        return $this;
    }

    /**
     * @param string|null $ks
     * @return InvoiceDto
     */
    public function setKs(?string $ks): InvoiceDto
    {
        $this->ks = $ks;
        return $this;
    }

    /**
     * @param string|null $currency
     * @return InvoiceDto
     */
    public function setCurrency(?string $currency): InvoiceDto
    {
        $this->currency = $currency;
        return $this;
    }

    /**
     * @return InvoiceItemDto[]
     */
    public function getInvoiceItems(): array
    {
        return $this->invoiceItems;
    }

    /**
     * @param InvoiceItemDto[] $invoiceItems
     */
    public function setInvoiceItems(array $invoiceItems): InvoiceDto
    {
        $this->invoiceItems = $invoiceItems;
        return $this;
    }

}
