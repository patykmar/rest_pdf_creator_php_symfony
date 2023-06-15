<?php

namespace App\Entity;

use App\Repository\InvoiceItemEntityRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InvoiceItemEntityRepository::class)]
class InvoiceItemEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $vat = null;

    #[ORM\Column(length: 255)]
    private ?string $itemName = null;

    #[ORM\Column]
    private ?float $price = null;

    #[ORM\Column]
    private ?float $unitCount = null;

    #[ORM\ManyToOne(inversedBy: 'invoiceItemEntities')]
    private ?InvoiceEntity $invoice = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getVat(): ?int
    {
        return $this->vat;
    }

    public function setVat(int $vat): static
    {
        $this->vat = $vat;

        return $this;
    }

    public function getItemName(): ?string
    {
        return $this->itemName;
    }

    public function setItemName(string $itemName): static
    {
        $this->itemName = $itemName;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getUnitCount(): ?float
    {
        return $this->unitCount;
    }

    public function setUnitCount(float $unitCount): static
    {
        $this->unitCount = $unitCount;

        return $this;
    }

    public function getInvoice(): InvoiceEntity
    {
        return $this->invoice;
    }

    public function setInvoice(?InvoiceEntity $invoice): static
    {
        $this->invoice = $invoice;

        return $this;
    }

}
