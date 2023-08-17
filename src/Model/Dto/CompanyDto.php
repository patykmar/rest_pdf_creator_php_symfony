<?php

namespace App\Model\Dto;

use Symfony\Component\Validator\Constraints as Assert;

final class CompanyDto
{
    private ?int $id = null;
    private string $name;
    private AddressDto $address;
    private ?string $companyId = null;
    #[Assert\NotBlank]
    private ?string $vatNumber = null;
    #[Assert\NotBlank]
    private ?string $bankAccountNumber = null;
    private ?string $iban = null;
    private ?string $swift = null;
    private ?string $signature = null;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     * @return CompanyDto
     */
    public function setId(?int $id): self
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @param string $name
     * @return CompanyDto
     */
    public function setName(string $name): CompanyDto
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @param AddressDto $address
     * @return CompanyDto
     */
    public function setAddress(AddressDto $address): CompanyDto
    {
        $this->address = $address;
        return $this;
    }

    /**
     * @param string|null $companyId
     * @return CompanyDto
     */
    public function setCompanyId(?string $companyId): CompanyDto
    {
        $this->companyId = $companyId;
        return $this;
    }

    /**
     * @param string|null $vatNumber
     * @return CompanyDto
     */
    public function setVatNumber(?string $vatNumber): CompanyDto
    {
        $this->vatNumber = $vatNumber;
        return $this;
    }

    /**
     * @param string|null $bankAccountNumber
     * @return CompanyDto
     */
    public function setBankAccountNumber(?string $bankAccountNumber): CompanyDto
    {
        $this->bankAccountNumber = $bankAccountNumber;
        return $this;
    }

    /**
     * @param string|null $swift
     * @return CompanyDto
     */
    public function setSwift(?string $swift): CompanyDto
    {
        $this->swift = $swift;
        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return AddressDto
     */
    public function getAddress(): AddressDto
    {
        return $this->address;
    }

    /**
     * @return string|null
     */
    public function getCompanyId(): ?string
    {
        return $this->companyId;
    }

    /**
     * @return string|null
     */
    public function getVatNumber(): ?string
    {
        return $this->vatNumber;
    }

    /**
     * @return string|null
     */
    public function getBankAccountNumber(): ?string
    {
        return $this->bankAccountNumber;
    }

    /**
     * @return string|null
     */
    public function getSwift(): ?string
    {
        return $this->swift;
    }

    /**
     * @return string|null
     */
    public function getIban(): ?string
    {
        return $this->iban;
    }

    /**
     * @param string|null $iban
     * @return CompanyDto
     */
    public function setIban(?string $iban): self
    {
        $this->iban = $iban;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getSignature(): ?string
    {
        return $this->signature;
    }

    /**
     * @param string|null $signature
     * @return CompanyDto
     */
    public function setSignature(?string $signature): self
    {
        $this->signature = $signature;
        return $this;
    }

}
