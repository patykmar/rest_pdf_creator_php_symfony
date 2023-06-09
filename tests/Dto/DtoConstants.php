<?php

namespace App\Tests\Dto;

use App\Model\Dto\AddressDto;
use App\Model\Dto\CompanyDto;
use App\Model\Dto\InvoiceDto;
use App\Model\Dto\InvoiceItemDto;

class DtoConstants
{
    private AddressDto $addressDto;
    private CompanyDto $companyDto;
    private InvoiceDto $invoiceDto;

    /** @var AddressDto[] */
    private array $addressesDto = array();
    /** @var CompanyDto[] */
    private array $companiesDto = array();
    /** @var InvoiceDto[] */
    private array $invoicesDto = array();

    public function __construct()
    {
        $this->populateAddressDto();
        $this->populateCompanyDto();
        $this->populateInvoiceDto();
    }

    private function populateAddressDto(): void
    {
        array_push($this->addressesDto,
            $this->makeAddressDto("Fiction USA", "Fake street 123", "Springfield", "110 00"),
            $this->makeAddressDto("Fiction USA", "Fake street 321", "Springfield", "220 00"),
            $this->makeAddressDto("Fiction USA", "Fake street 231", "Springfield", "330 00"),
        );
        $this->addressDto = $this->addressesDto[0];
    }

    private function populateCompanyDto(): void
    {
        array_push($this->companiesDto,
            $this->makeCompanyDto("Company name 1", $this->addressesDto[0], "1234567890", null, "1234-567890123/1234", null),
            $this->makeCompanyDto("Company name 2", $this->addressesDto[1], "2345678901", null, "2345-567890123/1234", null),
            $this->makeCompanyDto("Company name 3", $this->addressesDto[2], "3456789012", null, "3456-567890123/1234", null),
        );
        $this->companyDto = $this->companiesDto[0];
    }

    private function populateInvoiceDto(): void
    {
        array_push($this->invoicesDto,
            $this->makeInvoiceDto($this->companiesDto[0], $this->companiesDto[1], 'transfer', 12345678, 14, null, null, "eur", $this->populateInvoiceItemDto()),
            $this->makeInvoiceDto($this->companiesDto[0], $this->companiesDto[1], 'transfer', 12345678, 14, null, null, "CZK", $this->populateInvoiceItemDto()),
            $this->makeInvoiceDto($this->companiesDto[1], $this->companiesDto[2], 'cache', 12345678, 14, null, null, "USD", $this->populateInvoiceItemDto()),
            $this->makeInvoiceDto($this->companiesDto[1], $this->companiesDto[2], 'cache', 12345678, 14, null, null, "PLN", $this->populateInvoiceItemDto()),
        );
        $this->invoiceDto = $this->invoicesDto[0];
    }

    /**
     * @return InvoiceItemDto[]
     */
    private function populateInvoiceItemDto(): array
    {
        // int $vat, string $itemName, float $price, float $unitCount
        return [
            $this->makeInvoiceItemDto(0, "Invoice item 01", 12345.1234, 1.0),
            $this->makeInvoiceItemDto(0, "Invoice item 02", 12345.1234, 1.2),
            $this->makeInvoiceItemDto(0, "Invoice item 03", 12345.1234, 12.3),
            $this->makeInvoiceItemDto(0, "Invoice item 04", 12345.1234, 123.4),
            $this->makeInvoiceItemDto(0, "Invoice item 05", 12345.1234, 123.45)
        ];
    }

    public function makeAddressDto(string $country, string $street, string $city, string $zipCode): AddressDto
    {
        $returnAddressDto = new AddressDto();
        return $returnAddressDto
            ->setCountry($country)
            ->setStreet($street)
            ->setCity($city)
            ->setZipCode($zipCode);
    }

    public function makeCompanyDto(
        string     $name,
        AddressDto $addressDto,
        ?string    $companyId,
        ?string    $vatNumber,
        ?string    $bankAccountNumber,
        ?string    $swift
    ): CompanyDto
    {
        $companyDto = new CompanyDto();
        return $companyDto
            ->setName($name)
            ->setAddress($addressDto)
            ->setCompanyId($companyId)
            ->setVatNumber($vatNumber)
            ->setBankAccountNumber($bankAccountNumber)
            ->setSwift($swift);

    }

    public function makeInvoiceDto(
        CompanyDto $supplier,
        CompanyDto $subscriber,
        string     $paymentType,
        ?int       $created,
        ?int       $dueDay,
        ?string    $vs,
        ?string    $ks,
        ?string    $currency,
        array      $invoiceItems
    ): InvoiceDto
    {
        $invoiceDto = new InvoiceDto();
        return $invoiceDto
            ->setSupplier($supplier)
            ->setSubscriber($subscriber)
            ->setPaymentType($paymentType)
            ->setCreated($created)
            ->setDueDay($dueDay)
            ->setVs($vs)
            ->setKs($ks)
            ->setCurrency($currency)
            ->setInvoiceItems($invoiceItems);
    }

    public function makeInvoiceItemDto(int $vat, string $itemName, float $price, float $unitCount): InvoiceItemDto
    {
        $invoiceItemDto = new InvoiceItemDto();
        return $invoiceItemDto
            ->setVat($vat)
            ->setItemName($itemName)
            ->setPrice($price)
            ->setUnitCount($unitCount);
    }

    public function getAddressDto(): AddressDto
    {
        return $this->addressDto;
    }

    public function getCompanyDto(): CompanyDto
    {
        return $this->companyDto;
    }

    public function getInvoiceDto(): InvoiceDto
    {
        return $this->invoiceDto;
    }

    /**
     * @return AddressDto[]
     */
    public function getAddressesDto(): array
    {
        return $this->addressesDto;
    }

    /**
     * @return CompanyDto[]
     */
    public function getCompaniesDto(): array
    {
        return $this->companiesDto;
    }

    /**
     * @return InvoiceDto[]
     */
    public function getInvoicesDto(): array
    {
        return $this->invoicesDto;
    }

}
