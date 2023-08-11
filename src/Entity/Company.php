<?php

namespace App\Entity;

use App\Repository\CompanyRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CompanyRepository::class)]
final class Company implements EntityInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $country = null;

    #[ORM\Column(length: 255)]
    private ?string $street = null;

    #[ORM\Column(length: 255)]
    private ?string $city = null;

    #[ORM\Column(length: 255)]
    private ?string $zipCode = null;

    #[ORM\Column(length: 255)]
    private ?string $companyId = null;

    #[ORM\Column(length: 255)]
    private ?string $vatNumber = null;

    #[ORM\Column(length: 255)]
    private ?string $bankAccountNumber = null;

    #[ORM\Column(length: 255)]
    private ?string $iban = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $swift = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $signature = null;

    #[ORM\OneToMany(mappedBy: 'supplier', targetEntity: Invoice::class, orphanRemoval: true)]
    private Collection $supplierInvoices;

    #[ORM\OneToMany(mappedBy: 'subscriber', targetEntity: Invoice::class, orphanRemoval: true)]
    private Collection $subscriberInvoices;

    public function __construct()
    {
        $this->supplierInvoices = new ArrayCollection();
        $this->subscriberInvoices = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     * @return Company
     */
    public function setId(?int $id): Company
    {
        $this->id = $id;
        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): Company
    {
        $this->name = $name;
        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): Company
    {
        $this->country = $country;
        return $this;
    }

    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function setStreet(string $street): Company
    {
        $this->street = $street;
        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): Company
    {
        $this->city = $city;
        return $this;
    }

    public function getZipCode(): ?string
    {
        return $this->zipCode;
    }

    public function setZipCode(string $zipCode): Company
    {
        $this->zipCode = $zipCode;
        return $this;
    }

    public function getCompanyId(): ?string
    {
        return $this->companyId;
    }

    public function setCompanyId(string $companyId): Company
    {
        $this->companyId = $companyId;
        return $this;
    }

    public function getVatNumber(): ?string
    {
        return $this->vatNumber;
    }

    public function setVatNumber(?string $vatNumber): Company
    {
        $this->vatNumber = $vatNumber;
        return $this;
    }

    public function getBankAccountNumber(): ?string
    {
        return $this->bankAccountNumber;
    }

    public function setBankAccountNumber(string $bankAccountNumber): Company
    {
        $this->bankAccountNumber = $bankAccountNumber;
        return $this;
    }

    public function getIban(): ?string
    {
        return $this->iban;
    }

    public function setIban(?string $iban): Company
    {
        $this->iban = $iban;
        return $this;
    }

    public function getSwift(): ?string
    {
        return $this->swift;
    }

    public function setSwift(?string $swift): Company
    {
        $this->swift = $swift;
        return $this;
    }

    public function getSignature(): ?string
    {
        return $this->signature;
    }

    public function setSignature(?string $signature): Company
    {
        $this->signature = $signature;
        return $this;
    }

    /**
     * @return Collection<int, Invoice>
     */
    public function getSupplierInvoices(): Collection
    {
        return $this->supplierInvoices;
    }

    public function addSupplierInvoice(Invoice $supplierInvoice): Company
    {
        if (!$this->supplierInvoices->contains($supplierInvoice)) {
            $this->supplierInvoices->add($supplierInvoice);
            $supplierInvoice->setSupplier($this);
        }

        return $this;
    }

    public function removeSupplierInvoice(Invoice $supplierInvoice): Company
    {
        if ($this->supplierInvoices->removeElement($supplierInvoice)) {
            // set the owning side to null (unless already changed)
            if ($supplierInvoice->getSupplier() === $this) {
                $supplierInvoice->setSupplier(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Invoice>
     */
    public function getSubscriberInvoices(): Collection
    {
        return $this->subscriberInvoices;
    }

    public function addSubscriberInvoice(Invoice $subscriberInvoice): Company
    {
        if (!$this->subscriberInvoices->contains($subscriberInvoice)) {
            $this->subscriberInvoices->add($subscriberInvoice);
            $subscriberInvoice->setSubscriber($this);
        }

        return $this;
    }

    public function removeSubscriberInvoice(Invoice $subscriberInvoice): Company
    {
        if ($this->subscriberInvoices->removeElement($subscriberInvoice)) {
            // set the owning side to null (unless already changed)
            if ($subscriberInvoice->getSubscriber() === $this) {
                $subscriberInvoice->setSubscriber(null);
            }
        }

        return $this;
    }
}
