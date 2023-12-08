<?php

namespace App\Tests\Mapper\Trait;

use App\Entity\Company;
use App\Model\DataDto\InvoiceDataDto;
use App\Model\Dto\AddressDto;
use App\Model\Dto\CompanyDto;
use App\Model\Dto\InvoiceItemDto;

trait CommonAsserTrait
{
    public function assertCompanyToCompanyDto(Company $sourceEntity, CompanyDto $destinationDto): void
    {
        $this->assertCompanyDtoNotNull($destinationDto);

        $this->assertInstanceOf(CompanyDto::class, $destinationDto);
        $this->assertSame($sourceEntity->getCompanyId(), $destinationDto->getCompanyId());
        $this->assertSame($sourceEntity->getIban(), $destinationDto->getIban());
        $this->assertSame($sourceEntity->getId(), $destinationDto->getId());
        $this->assertSame($sourceEntity->getSignature(), $destinationDto->getSignature());
        $this->assertSame($sourceEntity->getName(), $destinationDto->getName());
        $this->assertSame($sourceEntity->getVatNumber(), $destinationDto->getVatNumber());
        $this->assertSame($sourceEntity->getCity(), $destinationDto->getAddress()->getCity());
        $this->assertSame($sourceEntity->getCountry(), $destinationDto->getAddress()->getCountry());
        $this->assertSame($sourceEntity->getStreet(), $destinationDto->getAddress()->getStreet());
        $this->assertSame($sourceEntity->getZipCode(), $destinationDto->getAddress()->getZipCode());
    }

    public function assertCompanyDtoToCompany(CompanyDto $sourceCompanyDto, Company $resultCompany): void
    {
        $this->assertInstanceOf(Company::class, $resultCompany);
        $this->assertSame($sourceCompanyDto->getCompanyId(), $resultCompany->getCompanyId());
        $this->assertSame($sourceCompanyDto->getIban(), $resultCompany->getIban());
        $this->assertSame($sourceCompanyDto->getId(), $resultCompany->getId());
        $this->assertSame($sourceCompanyDto->getSignature(), $resultCompany->getSignature());
        $this->assertSame($sourceCompanyDto->getName(), $resultCompany->getName());
        $this->assertSame($sourceCompanyDto->getVatNumber(), $resultCompany->getVatNumber());
        $this->assertSame($sourceCompanyDto->getAddress()->getCity(), $resultCompany->getCity());
        $this->assertSame($sourceCompanyDto->getAddress()->getCountry(), $resultCompany->getCountry());
        $this->assertSame($sourceCompanyDto->getAddress()->getStreet(), $resultCompany->getStreet());
        $this->assertSame($sourceCompanyDto->getAddress()->getZipCode(), $resultCompany->getZipCode());
    }

    public function assertInvoiceNotNull(InvoiceDataDto $invoiceDataDto): void
    {
        $this->assertNotNull($invoiceDataDto->getId());
        $this->assertNotNull($invoiceDataDto->getDescription());
        $this->assertNotNull($invoiceDataDto->getPaymentType());
        $this->assertNotNull($invoiceDataDto->getCreated());
        $this->assertNotNull($invoiceDataDto->getDueDay());
        $this->assertNotNull($invoiceDataDto->getVs());
        $this->assertNotNull($invoiceDataDto->getKs());
        $this->assertNotNull($invoiceDataDto->getCurrency());
        $this->assertNotNull($invoiceDataDto->getInvoiceItems());
        $this->assertCompanyDtoNotNull($invoiceDataDto->getSupplier());
        $this->assertCompanyDtoNotNull($invoiceDataDto->getSubscriber());

        foreach ($invoiceDataDto->getInvoiceItems() as $invoiceItemDto) {
            $this->assertInvoiceItemDtoNotNull($invoiceItemDto);
        }
    }

    public function assertCompanyDtoNotNull(CompanyDto $companyDto): void
    {
        $this->assertNotNull($companyDto);
        $this->assertNotNull($companyDto->getName());
        $this->assertNotNull($companyDto->getName());
        $this->assertAddressDtoNotNull($companyDto->getAddress());
        $this->assertNotNull($companyDto->getCompanyId());
        $this->assertNotNull($companyDto->getVatNumber());
        $this->assertNotNull($companyDto->getBankAccountNumber());
        $this->assertNotNull($companyDto->getIban());
        $this->assertNotNull($companyDto->getSwift());
        $this->assertNotNull($companyDto->getSignature());
    }

    public function assertAddressDtoNotNull(AddressDto $addressDto): void
    {
        $this->assertNotNull($addressDto);
        $this->assertNotNull($addressDto->getCountry());
        $this->assertNotNull($addressDto->getStreet());
        $this->assertNotNull($addressDto->getCity());
        $this->assertNotNull($addressDto->getZipCode());
    }

    public function assertInvoiceItemDtoNotNull(InvoiceItemDto $sourceInvoiceItemDto): void
    {
        $this->assertNotNull($sourceInvoiceItemDto);
        $this->assertNotNull($sourceInvoiceItemDto->getVat());
        $this->assertNotNull($sourceInvoiceItemDto->getItemName());
        $this->assertNotNull($sourceInvoiceItemDto->getPrice());
        $this->assertNotNull($sourceInvoiceItemDto->getUnitCount());
    }

    /**
     * Compare two same data type {@see InvoiceItemDto} if there is same value
     */
    public function assertInvoiceItemDtos(InvoiceItemDto $invoiceItemDtoExpected, InvoiceItemDto $invoiceItemDtoResult): void
    {
        $this->assertIsNumeric($invoiceItemDtoResult->getId());
        $this->assertSame($invoiceItemDtoExpected->getVat(), $invoiceItemDtoResult->getVat());
        $this->assertSame($invoiceItemDtoExpected->getItemName(), $invoiceItemDtoResult->getItemName());
        $this->assertSame($invoiceItemDtoExpected->getPrice(), $invoiceItemDtoResult->getPrice());
        $this->assertSame($invoiceItemDtoExpected->getUnitCount(), $invoiceItemDtoResult->getUnitCount());
    }

}
