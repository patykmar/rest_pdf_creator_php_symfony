<?php

namespace App\Model\Dto;

class CompanyDto
{
    private string $name;
    private AddressDto $address;
    private ?string $companyId = null;
    private ?string $vatNumber = null;
    private ?string $bankAccountNumber = null;
    private ?string $swift = null;

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

}
