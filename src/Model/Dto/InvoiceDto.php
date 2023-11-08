<?php

namespace App\Model\Dto;

use Symfony\Component\Validator\Constraints as Assert;

final class InvoiceDto
{
    #[Assert\NotBlank]
    private int $supplierId;
    #[Assert\NotBlank]
    private int $subscriberId;
    #[Assert\NotBlank]
    private string $description;
    #[Assert\NotBlank]
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

    public function __construct()
    {
        $this->invoiceItems = array();
    }

    /**
     * @return int
     */
    public function getSupplierId(): int
    {
        return $this->supplierId;
    }

    /**
     * @return int
     */
    public function getSubscriberId(): int
    {
        return $this->subscriberId;
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
     * @return array
     */
    public function getInvoiceItems(): array
    {
        return $this->invoiceItems;
    }

    public function getDescription(): string
    {
        return $this->description;
    }


    /**
     * @param int $supplierId
     * @return InvoiceDto
     */
    public function setSupplierId(int $supplierId): InvoiceDto
    {
        $this->supplierId = $supplierId;
        return $this;
    }

    /**
     * @param int $subscriberId
     * @return InvoiceDto
     */
    public function setSubscriberId(int $subscriberId): InvoiceDto
    {
        $this->subscriberId = $subscriberId;
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
     * @psalm-param InvoiceItemDto[] $invoiceItems
     */
    public function setInvoiceItems(array $invoiceItems): InvoiceDto
    {
        $this->invoiceItems = $invoiceItems;
        return $this;
    }

    public function addInvoiceItem(InvoiceItemDto $invoiceItemDto): void
    {
        $this->invoiceItems[] = $invoiceItemDto;
    }

    public function setDescription(string $description): InvoiceDto
    {
        $this->description = $description;
        return $this;
    }

}
