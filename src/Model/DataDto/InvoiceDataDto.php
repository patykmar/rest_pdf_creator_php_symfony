<?php

namespace App\Model\DataDto;

use App\Model\Dto\CompanyDto;
use App\Model\Dto\InvoiceItemDto;

final class InvoiceDataDto
{
    private ?int $id;
    private ?string $description;
    private CompanyDto $supplier;
    private CompanyDto $subscriber;
    private ?string $paymentType = null;
    private ?int $created = null;
    private ?int $dueDay = null;
    private ?string $vs = null;
    private ?string $ks = null;
    private ?string $currency = null;

    /**
     * @var InvoiceItemDto[]
     */
    private array $invoiceItems;

    public function __construct()
    {
        $this->invoiceItems = array();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): InvoiceDataDto
    {
        $this->id = $id;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): InvoiceDataDto
    {
        $this->description = $description;
        return $this;
    }

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
     * @return string|null
     */
    public function getPaymentType(): ?string
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
     * @return InvoiceDataDto
     */
    public function setSupplier(CompanyDto $supplier): InvoiceDataDto
    {
        $this->supplier = $supplier;
        return $this;
    }

    /**
     * @param CompanyDto $subscriber
     * @return InvoiceDataDto
     */
    public function setSubscriber(CompanyDto $subscriber): InvoiceDataDto
    {
        $this->subscriber = $subscriber;
        return $this;
    }

    /**
     * @param string|null $paymentType
     * @return InvoiceDataDto
     */
    public function setPaymentType(?string $paymentType): InvoiceDataDto
    {
        $this->paymentType = $paymentType;
        return $this;
    }

    /**
     * @param int|null $created
     * @return InvoiceDataDto
     */
    public function setCreated(?int $created): InvoiceDataDto
    {
        $this->created = $created;
        return $this;
    }

    /**
     * @param int|null $dueDay
     * @return InvoiceDataDto
     */
    public function setDueDay(?int $dueDay): InvoiceDataDto
    {
        $this->dueDay = $dueDay;
        return $this;
    }

    /**
     * @param string|null $vs
     * @return InvoiceDataDto
     */
    public function setVs(?string $vs): InvoiceDataDto
    {
        $this->vs = $vs;
        return $this;
    }

    /**
     * @param string|null $ks
     * @return InvoiceDataDto
     */
    public function setKs(?string $ks): InvoiceDataDto
    {
        $this->ks = $ks;
        return $this;
    }

    /**
     * @param string|null $currency
     * @return InvoiceDataDto
     */
    public function setCurrency(?string $currency): InvoiceDataDto
    {
        $this->currency = $currency;
        return $this;
    }

    /**
     * @psalm-return InvoiceItemDto[]
     */
    public function getInvoiceItems(): array
    {
        return $this->invoiceItems;
    }

    /**
     * @psalm-param InvoiceItemDto[] $invoiceItems
     */
    public function setInvoiceItems(array $invoiceItems): InvoiceDataDto
    {
        $this->invoiceItems = $invoiceItems;
        return $this;
    }

    public function addInvoiceItem(InvoiceItemDto $invoiceItemDto): void
    {
        $this->invoiceItems[] = $invoiceItemDto;
    }

}
