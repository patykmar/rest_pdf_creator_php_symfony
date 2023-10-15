<?php

namespace App\Tests\Mock\Dto;

use App\Model\DataDto\InvoiceDataDto;
use App\Model\Dto\AddressDto;
use App\Model\Dto\CompanyDto;
use App\Model\Dto\InvoiceDto;
use App\Model\Dto\InvoiceItemDto;
use Doctrine\Common\Collections\ArrayCollection;

class DtoMock
{
    public const COMPANY_COUNT = 20;
    public const INVOICE_ITEM_COUNT = 10;
    public const INVOICE_COUNT = 10;
    public const INVOICE_DUE_DAY = 14;

    private static function getPaymentType(int $id): string
    {
        $paymentType = ['transfer', 'cache'];
        $idMod = $id % count($paymentType);
        return $paymentType[$idMod];
    }

    private static function getCurrency(int $id): string
    {
        $currency = ["eur", "CZK", "USD", "PLN"];
        $idMod = $id % count($currency);
        return $currency[$idMod];
    }


    private static function generateVs(int $id): string
    {
        return date("Y") . "000" . $id;
    }

    private static function generateKs(int $id): string
    {
        return "000" . $id;
    }

    private static function makeInvoiceDto(int $id): InvoiceDto
    {
        $invoiceDto = self::makeMinimalInvoice($id, $id + 1);
        return $invoiceDto
            ->setSupplierId($id)
            ->setSubscriberId($id + 1)
            ->setPaymentType(self::getPaymentType($id))
            ->setCreated(123456 + $id * 100)
            ->setDueDay(self::INVOICE_DUE_DAY)
            ->setVs(self::generateVs($id))
            ->setKs(self::generateKs($id))
            ->setCurrency(self::getCurrency($id))
            ->setInvoiceItems(self::populateInvoiceItemDto()->toArray());
    }

    /**
     * @psalm-return ArrayCollection<InvoiceDto>
     */
    private static function populateInvoiceDto(): ArrayCollection
    {
        $returnInvoices = new ArrayCollection();
        for ($i = 1; $i <= self::INVOICE_COUNT; $i++) {
            $returnInvoices->add(self::makeInvoiceDto($i));
        }
        return $returnInvoices;
    }

    private static function makeInvoiceDataDto(int $id): InvoiceDataDto
    {
        $invoiceDataDto = new InvoiceDataDto();
        return $invoiceDataDto
            ->setSupplier(self::makeCompanyDto($id))
            ->setSubscriber(self::makeCompanyDto($id + 1))
            ->setPaymentType(self::getPaymentType($id))
            ->setCreated(12345678)
            ->setDueDay(14)
            ->setVs(self::generateVs($id))
            ->setKs(self::generateKs($id))
            ->setCurrency(self::getCurrency($id))
            ->setInvoiceItems(self::populateInvoiceItemDto());
    }

    public static function getInvoiceDataDto($id = 1): InvoiceDataDto
    {
        return self::makeInvoiceDataDto($id);
    }

    /**
     * @psalm-return ArrayCollection<InvoiceItemDto>
     */
    private static function populateInvoiceItemDto(): ArrayCollection
    {
        $returnInvoiceItems = new ArrayCollection();
        for ($i = 0; $i < self::INVOICE_ITEM_COUNT; $i++) {
            $returnInvoiceItems->add(self::makeInvoiceItemWithNoVatDto($i));
        }
        return $returnInvoiceItems;
    }

    private static function populateInvoiceItemAsArray(): array
    {
        $result = array();
        for ($i = 0; $i < self::INVOICE_ITEM_COUNT; $i++) {
            $result[] = (self::makeInvoiceItemWithNoVatDtoAsArray($i));
        }
        return $result;
    }

    private static function makeInvoiceItemWithNoVatDto(int $id): InvoiceItemDto
    {
        $invoiceItemDto = new InvoiceItemDto();
        return $invoiceItemDto
            ->setVat(0)
            ->setItemName("Invoice item 0" . $id)
            ->setPrice(10000.0 + ($id / 10) + ($id * 10))
            ->setUnitCount(1.0 * $id / 10);
    }

    private static function makeInvoiceItemWithNoVatDtoAsArray(int $id): array
    {
        $invoiceItemDto = self::makeInvoiceItemWithNoVatDto($id);
        return [
            'vat' => $invoiceItemDto->getVat(),
            'itemName' => $invoiceItemDto->getItemName(),
            'price' => $invoiceItemDto->getPrice(),
            'unitCount' => $invoiceItemDto->getUnitCount()
        ];
    }

    public static function makeMinimalInvoice(int $supplierId = 1, int $subscriberId = 2): InvoiceDto
    {
        $invoiceDto = new InvoiceDto();
        return $invoiceDto
            ->setSupplierId($supplierId)
            ->setSubscriberId($subscriberId);
    }

    public static function makeMinimalInvoiceWithItemsAsArray(): InvoiceDto
    {
        $invoiceDto = self::makeMinimalInvoice();
        $invoiceDto->setInvoiceItems(self::populateInvoiceItemAsArray());
        return $invoiceDto;
    }

    /**
     * @param int $supplierId
     * @param int $subscriberId
     * @return InvoiceDto
     * Return {@link InvoiceDto} with fulfilled supplierId and subscriberId and
     * invoice items.
     */
    public static function makeMinimalInvoiceDtoWithInvoiceItems(int $supplierId = 1, int $subscriberId = 2): InvoiceDto
    {
        $invoiceDto = self::makeMinimalInvoice($supplierId, $subscriberId);
        return $invoiceDto
            ->setInvoiceItems(self::populateInvoiceItemDto()->toArray());
    }


    private static function makeAddressDto(int $id): AddressDto
    {
        $addressDto = new AddressDto();
        return $addressDto->setStreet("Fake street " . $id)
            ->setCity("Fake city " . $id)
            ->setCountry("Fake country " . $id)
            ->setZipCode("1234" . $id);
    }

    private static function makeCompanyDto(int $id): CompanyDto
    {
        $companyDto = new CompanyDto();
        return $companyDto
            ->setName("Fake company name " . $id)
            ->setAddress(DtoMock::makeAddressDto($id))
            ->setCompanyId($id . "234567890")
            ->setVatNumber($id . "345678901")
            ->setBankAccountNumber($id . "34-567890123/1234")
            ->setIban("CZ650800000019200014539" . $id)
            ->setSwift("AIRACZPP1234567890" . $id)
            ->setSignature("CZ0000123412345678901234. $id");
    }

    /**
     * @psalm-return ArrayCollection<CompanyDto>
     */
    private static function populateCompanyDto(): ArrayCollection
    {
        $returnArrayCollection = new ArrayCollection();
        for ($i = 1; $i <= self::COMPANY_COUNT; $i++) {
            $returnArrayCollection->add(DtoMock::makeCompanyDto($i));
        }
        return $returnArrayCollection;
    }

    public static function getCompanyDto(int $index = 0): CompanyDto
    {
        return self::getCompaniesDto()->get($index);
    }

    public static function getInvoiceDto(int $index = 0): InvoiceDto
    {
        return self::populateInvoiceDto()->get($index);
    }

    /**
     * @return ArrayCollection<CompanyDto>
     */
    public static function getCompaniesDto(): ArrayCollection
    {
        return DtoMock::populateCompanyDto();
    }

}
