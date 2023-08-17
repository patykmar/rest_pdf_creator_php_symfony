<?php

namespace App\Entity;

use App\Repository\InvoiceRepository;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InvoiceRepository::class)]
final class Invoice
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

    #[ORM\OneToMany(mappedBy: 'invoice', targetEntity: InvoiceItemEntity::class)]
    private Collection $invoiceItemEntities;

    #[ORM\ManyToOne(inversedBy: 'supplierInvoices')]
    #[ORM\JoinColumn(nullable: false)]
    private Company $supplier;

    #[ORM\ManyToOne(inversedBy: 'subscriberInvoices')]
    #[ORM\JoinColumn(nullable: false)]
    private Company $subscriber;

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

    public function setDescription(string $description): Invoice
    {
        $this->description = $description;

        return $this;
    }

    public function getPaymentType(): ?string
    {
        return $this->paymentType;
    }

    public function setPaymentType(string $paymentType): Invoice
    {
        $this->paymentType = $paymentType;

        return $this;
    }

    public function getCreated(): ?DateTimeInterface
    {
        return $this->created;
    }

    public function setCreated(DateTimeInterface $created): Invoice
    {
        $this->created = $created;

        return $this;
    }

    public function getDueDay(): ?int
    {
        return $this->dueDay;
    }

    public function setDueDay(int $dueDay): Invoice
    {
        $this->dueDay = $dueDay;

        return $this;
    }

    public function getVs(): ?string
    {
        return $this->vs;
    }

    public function setVs(string $vs): Invoice
    {
        $this->vs = $vs;

        return $this;
    }

    public function getKs(): ?string
    {
        return $this->ks;
    }

    public function setKs(string $ks): Invoice
    {
        $this->ks = $ks;

        return $this;
    }

    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    public function setCurrency(string $currency): Invoice
    {
        $this->currency = $currency;

        return $this;
    }

    public function __toString(): string
    {
        return printf("%s, %d - %s, %d",
            $this->getId(),
            $this->getVs(),
            $this->getDescription(),
            $this->getCurrency(),

        );
    }

    /**
     * @return Collection<int, InvoiceItemEntity>
     */
    public function getInvoiceItemEntities(): Collection
    {
        return $this->invoiceItemEntities;
    }

    public function addInvoiceItemEntity(InvoiceItemEntity $invoiceItemEntity): Invoice
    {
        if (!$this->invoiceItemEntities->contains($invoiceItemEntity)) {
            $this->invoiceItemEntities->add($invoiceItemEntity);
            $invoiceItemEntity->setInvoice($this);
        }

        return $this;
    }

    public function removeInvoiceItemEntity(InvoiceItemEntity $invoiceItemEntity): Invoice
    {
        if ($this->invoiceItemEntities->removeElement($invoiceItemEntity)) {
            // set the owning side to null (unless already changed)
            if ($invoiceItemEntity->getInvoice() === $this) {
                $invoiceItemEntity->setInvoice(null);
            }
        }

        return $this;
    }

    public function getSupplier(): Company
    {
        return $this->supplier;
    }

    public function setSupplier(Company $supplier): Invoice
    {
        $this->supplier = $supplier;

        return $this;
    }

    public function getSubscriber(): Company
    {
        return $this->subscriber;
    }

    public function setSubscriber(Company $subscriber): Invoice
    {
        $this->subscriber = $subscriber;

        return $this;
    }

}
