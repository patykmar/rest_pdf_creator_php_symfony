<?php

namespace App\Model\Dto;

class InvoiceItemDto
{
    private int $vat;
    private string $itemName;
    private float $price;
    private float $unitCount;

    /**
     * @return int
     */
    public function getVat(): int
    {
        return $this->vat;
    }

    /**
     * @return string
     */
    public function getItemName(): string
    {
        return $this->itemName;
    }

    /**
     * @return float
     */
    public function getPrice(): float
    {
        return $this->price;
    }

    /**
     * @return float
     */
    public function getUnitCount(): float
    {
        return $this->unitCount;
    }

    /**
     * @param int $vat
     * @return InvoiceItemDto
     */
    public function setVat(int $vat): InvoiceItemDto
    {
        $this->vat = $vat;
        return $this;
    }

    /**
     * @param string $itemName
     * @return InvoiceItemDto
     */
    public function setItemName(string $itemName): InvoiceItemDto
    {
        $this->itemName = $itemName;
        return $this;
    }

    /**
     * @param float $price
     * @return InvoiceItemDto
     */
    public function setPrice(float $price): InvoiceItemDto
    {
        $this->price = $price;
        return $this;
    }

    /**
     * @param float $unitCount
     * @return InvoiceItemDto
     */
    public function setUnitCount(float $unitCount): InvoiceItemDto
    {
        $this->unitCount = $unitCount;
        return $this;
    }

}
