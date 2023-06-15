<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\InvoiceEntityRepository;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InvoiceEntityRepository::class)]
#[ApiResource]
class InvoiceEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column(length: 50)]
    private ?string $paymentType = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?DateTimeInterface $created = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $dueDay = null;

    #[ORM\Column(length: 50)]
    private ?string $vs = null;

    #[ORM\Column(length: 50)]
    private ?string $ks = null;

    #[ORM\Column(length: 3)]
    private ?string $currency = null;

    #[ORM\Column(length: 255)]
    private ?string $supplierName = null;

    #[ORM\Column]
    private ?string $supplierCompanyId = null;

    #[ORM\Column(length: 255)]
    private ?string $supplierVatNumber = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $supplierBankAccountNumber = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $supplierSwift = null;

    #[ORM\Column(length: 150)]
    private ?string $supplierAddressCountry = null;

    #[ORM\Column(length: 150)]
    private ?string $supplierAddressStreet = null;

    #[ORM\Column(length: 100)]
    private ?string $supplierAddressCity = null;

    #[ORM\Column(length: 100)]
    private ?string $supplierAddressZipCode = null;

    #[ORM\Column(length: 255)]
    private ?string $subscriberName = null;

    #[ORM\Column(length: 255)]
    private ?string $subscriberCompanyId = null;

    #[ORM\Column(length: 255)]
    private ?string $subscriberVatNumber = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $subscriberBankAccountNumber = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $subscriberSwift = null;

    #[ORM\Column(length: 150, nullable: true)]
    private ?string $subscriberAddressCountry = null;

    #[ORM\Column(length: 150, nullable: true)]
    private ?string $subscriberAddressStreet = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $subscriberAddressCity = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $subscriberAddressZipCode = null;

    #[ORM\OneToMany(mappedBy: 'invoice', targetEntity: InvoiceItemEntity::class)]
    private Collection $invoiceItemEntities;

    public function __construct()
    {
        $this->invoiceItemEntities = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getPaymentType(): ?string
    {
        return $this->paymentType;
    }

    public function setPaymentType(string $paymentType): static
    {
        $this->paymentType = $paymentType;

        return $this;
    }

    public function getCreated(): ?DateTimeInterface
    {
        return $this->created;
    }

    public function setCreated(DateTimeInterface $created): static
    {
        $this->created = $created;

        return $this;
    }

    public function getDueDay(): ?int
    {
        return $this->dueDay;
    }

    public function setDueDay(int $dueDay): static
    {
        $this->dueDay = $dueDay;

        return $this;
    }

    public function getVs(): ?string
    {
        return $this->vs;
    }

    public function setVs(string $vs): static
    {
        $this->vs = $vs;

        return $this;
    }

    public function getKs(): ?string
    {
        return $this->ks;
    }

    public function setKs(string $ks): static
    {
        $this->ks = $ks;

        return $this;
    }

    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    public function setCurrency(string $currency): static
    {
        $this->currency = $currency;

        return $this;
    }

    public function getSupplierName(): ?string
    {
        return $this->supplierName;
    }

    public function setSupplierName(string $supplierName): static
    {
        $this->supplierName = $supplierName;

        return $this;
    }

    public function getSupplierCompanyId(): ?string
    {
        return $this->supplierCompanyId;
    }

    public function setSupplierCompanyId(?string $supplierCompanyId): static
    {
        $this->supplierCompanyId = $supplierCompanyId;

        return $this;
    }

    public function getSupplierVatNumber(): ?string
    {
        return $this->supplierVatNumber;
    }

    public function setSupplierVatNumber(?string $supplierVatNumber): static
    {
        $this->supplierVatNumber = $supplierVatNumber;

        return $this;
    }

    public function getSupplierBankAccountNumber(): ?string
    {
        return $this->supplierBankAccountNumber;
    }

    public function setSupplierBankAccountNumber(?string $supplierBankAccountNumber): static
    {
        $this->supplierBankAccountNumber = $supplierBankAccountNumber;

        return $this;
    }

    public function getSupplierSwift(): ?string
    {
        return $this->supplierSwift;
    }

    public function setSupplierSwift(?string $supplierSwift): static
    {
        $this->supplierSwift = $supplierSwift;

        return $this;
    }

    public function getSupplierAddressCountry(): ?string
    {
        return $this->supplierAddressCountry;
    }

    public function setSupplierAddressCountry(string $supplierAddressCountry): static
    {
        $this->supplierAddressCountry = $supplierAddressCountry;

        return $this;
    }

    public function getSupplierAddressStreet(): ?string
    {
        return $this->supplierAddressStreet;
    }

    public function setSupplierAddressStreet(string $supplierAddressStreet): static
    {
        $this->supplierAddressStreet = $supplierAddressStreet;

        return $this;
    }

    public function getSupplierAddressCity(): ?string
    {
        return $this->supplierAddressCity;
    }

    public function setSupplierAddressCity(string $supplierAddressCity): static
    {
        $this->supplierAddressCity = $supplierAddressCity;

        return $this;
    }

    public function getSupplierAddressZipCode(): ?string
    {
        return $this->supplierAddressZipCode;
    }

    public function setSupplierAddressZipCode(string $supplierAddressZipCode): static
    {
        $this->supplierAddressZipCode = $supplierAddressZipCode;

        return $this;
    }

    public function getSubscriberName(): ?string
    {
        return $this->subscriberName;
    }

    public function setSubscriberName(string $subscriberName): static
    {
        $this->subscriberName = $subscriberName;

        return $this;
    }

    public function getSubscriberCompanyId(): ?string
    {
        return $this->subscriberCompanyId;
    }

    public function setSubscriberCompanyId(string $subscriberCompanyId): static
    {
        $this->subscriberCompanyId = $subscriberCompanyId;

        return $this;
    }

    public function getSubscriberVatNumber(): ?string
    {
        return $this->subscriberVatNumber;
    }

    public function setSubscriberVatNumber(?string $subscriberVatNumber): static
    {
        $this->subscriberVatNumber = $subscriberVatNumber;

        return $this;
    }

    public function getSubscriberBankAccountNumber(): ?string
    {
        return $this->subscriberBankAccountNumber;
    }

    public function setSubscriberBankAccountNumber(?string $subscriberBankAccountNumber): static
    {
        $this->subscriberBankAccountNumber = $subscriberBankAccountNumber;

        return $this;
    }

    public function getSubscriberSwift(): ?string
    {
        return $this->subscriberSwift;
    }

    public function setSubscriberSwift(?string $subscriberSwift): static
    {
        $this->subscriberSwift = $subscriberSwift;

        return $this;
    }

    public function getSubscriberAddressCountry(): ?string
    {
        return $this->subscriberAddressCountry;
    }

    public function setSubscriberAddressCountry(?string $subscriberAddressCountry): static
    {
        $this->subscriberAddressCountry = $subscriberAddressCountry;

        return $this;
    }

    public function getSubscriberAddressStreet(): ?string
    {
        return $this->subscriberAddressStreet;
    }

    public function setSubscriberAddressStreet(?string $subscriberAddressStreet): static
    {
        $this->subscriberAddressStreet = $subscriberAddressStreet;

        return $this;
    }

    public function getSubscriberAddressCity(): ?string
    {
        return $this->subscriberAddressCity;
    }

    public function setSubscriberAddressCity(?string $subscriberAddressCity): static
    {
        $this->subscriberAddressCity = $subscriberAddressCity;

        return $this;
    }

    public function getSubscriberAddressZipCode(): ?string
    {
        return $this->subscriberAddressZipCode;
    }

    public function setSubscriberAddressZipCode(?string $subscriberAddressZipCode): static
    {
        $this->subscriberAddressZipCode = $subscriberAddressZipCode;

        return $this;
    }

    public function __toString(): string
    {
        return printf("%s, %d - %s, %d",
            $this->getSupplierName(),
            $this->getSupplierCompanyId(),
            $this->getSubscriberName(),
            $this->getSubscriberCompanyId(),

        );
    }

    /**
     * @return Collection<int, InvoiceItemEntity>
     */
    public function getInvoiceItemEntities(): Collection
    {
        return $this->invoiceItemEntities;
    }

    public function addInvoiceItemEntity(InvoiceItemEntity $invoiceItemEntity): static
    {
        if (!$this->invoiceItemEntities->contains($invoiceItemEntity)) {
            $this->invoiceItemEntities->add($invoiceItemEntity);
            $invoiceItemEntity->setInvoice($this);
        }

        return $this;
    }

    public function removeInvoiceItemEntity(InvoiceItemEntity $invoiceItemEntity): static
    {
        if ($this->invoiceItemEntities->removeElement($invoiceItemEntity)) {
            // set the owning side to null (unless already changed)
            if ($invoiceItemEntity->getInvoice() === $this) {
                $invoiceItemEntity->setInvoice(null);
            }
        }

        return $this;
    }

}
