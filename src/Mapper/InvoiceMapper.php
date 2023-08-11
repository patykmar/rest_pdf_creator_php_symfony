<?php

namespace App\Mapper;

use App\Entity\Invoice;
use App\Entity\InvoiceItemEntity;
use App\Exceptions\InvalidArgumentException;
use App\Model\DataDto\InvoiceDataDto;
use App\Model\Dto\CompanyDto;
use App\Model\Dto\InvoiceDto;
use App\Model\Dto\InvoiceItemDto;
use App\Service\InvoiceDefaultValuesService;
use DateTimeInterface;

class InvoiceMapper
{
    use MapperUtilsTrait;

    public function __construct(
        private readonly CompanyMapper               $companyMapper,
        private readonly InvoiceDefaultValuesService $defaultValuesService
    )
    {
    }

    public function toEntity(InvoiceDataDto $invoiceDto): Invoice
    {
        $invoiceEntity = new Invoice();
        $this->populateInvoiceItemEntity($invoiceDto, $invoiceEntity);
        return $invoiceEntity
            ->setPaymentType($this->nullSafeSetPaymentType($invoiceDto->getPaymentType()))
            ->setCreated($this->nullSafeSetCreatedTime($invoiceDto->getCreated()))
            ->setDueDay($this->nullSafeDueDay($invoiceDto->getDueDay()))
            ->setVs($this->nullSafeSetVs($invoiceDto->getVs()))
            ->setKs($this->nullSafeSetKs($invoiceDto->getKs()))
            ->setCurrency($this->nullSafeSetCurrency($invoiceDto->getCurrency()))
            ->setSupplier($this->companyMapper->toEntity($invoiceDto->getSupplier()))
            ->setSubscriber($this->companyMapper->toEntity($invoiceDto->getSubscriber()));
    }

    public function toInvoiceItemEntity(array|InvoiceItemDto $invoiceItemDto): InvoiceItemEntity
    {
        if (is_array($invoiceItemDto)) {
            return $this->fromArrayToInvoiceItemEntity($invoiceItemDto);
        } else {
            $invoiceItemEntity = new InvoiceItemEntity();
            return $invoiceItemEntity
                ->setVat($invoiceItemDto->getVat())
                ->setItemName($invoiceItemDto->getItemName())
                ->setPrice($invoiceItemDto->getPrice())
                ->setUnitCount($invoiceItemDto->getUnitCount());
        }
    }

    public function dtoToDataDto(InvoiceDto $invoiceDto, CompanyDto $supplier, CompanyDto $subscriber): InvoiceDataDto
    {
        $invoiceDataDto = new InvoiceDataDto();
        return $invoiceDataDto
            ->setSupplier($supplier)
            ->setSubscriber($subscriber)
            ->setInvoiceItems($invoiceDto->getInvoiceItems())
            ->setCreated($invoiceDto->getCreated())
            ->setDueDay($invoiceDto->getDueDay())
            ->setVs($invoiceDto->getVs())
            ->setKs($invoiceDto->getKs())
            ->setCurrency($invoiceDto->getCurrency());
    }

    private function fromArrayToInvoiceItemEntity(array $invoiceItem): InvoiceItemEntity
    {
        $validate = array_key_exists('vat', $invoiceItem) &&
            array_key_exists('itemName', $invoiceItem) &&
            array_key_exists('price', $invoiceItem) &&
            array_key_exists('unitCount', $invoiceItem);
        if (!$validate) {
            throw new InvalidArgumentException("Invoice item is not valid array");
        }
        $invoiceItemEntity = new InvoiceItemEntity();
        return $invoiceItemEntity
            ->setVat($invoiceItem['vat'])
            ->setItemName($invoiceItem['itemName'])
            ->setPrice($invoiceItem['price'])
            ->setUnitCount($invoiceItem['unitCount']);
    }

    /**
     * Check if the created time is set, if not set actual unix time
     */
    private function nullSafeSetCreatedTime(?int $created): DateTimeInterface
    {
        if (is_null($created)) {
            return $this->actualDateTime();
        }
        return $this->unixTimeToDateTime($created);
    }

    private function nullSafeSetVs(?string $vs): string
    {
        if (is_null($vs)) {
            return $this->nextInvoiceVsRandom($this->getActualYear());
        }
        return $vs;
    }

    /**
     * @param string $year
     * @return string
     * @throws InvalidArgumentException
     */
    private function nextInvoiceVsRandom(string $year): string
    {
        $vsLength = $this->defaultValuesService->getInvoiceDefaultValues()->getInvoiceVsLength();

        if ($vsLength >= 10) {
            throw new InvalidArgumentException("Const INVOICE_VS_LEN must be greater than 10");
        }
        $randomMaxValue = str_pad('9', $vsLength, '9', STR_PAD_LEFT);
        return $year . str_pad(rand(100, (int)$randomMaxValue), $vsLength, "0", STR_PAD_LEFT);
    }

    private function nullSafeSetKs(?string $ks): string
    {
        if (is_null($ks)) {
            return $this->defaultValuesService->getInvoiceDefaultValues()->getKs();
        }
        return $ks;
    }

    private function nullSafeSetCurrency(?string $currency): string
    {
        if (is_null($currency)) {
            return $this->defaultValuesService->getInvoiceDefaultValues()->getCurrencyCode();
        }
        return strtoupper($currency);
    }

    private function nullSafeDueDay(?int $dueDay): int
    {
        if (is_null($dueDay)) {
            return $this->defaultValuesService->getInvoiceDefaultValues()->getDueDay();
        }
        return $dueDay;
    }


    private function nullSafeSetPaymentType(?string $getPaymentType): string
    {
        if (is_null($getPaymentType)) {
            return $this->defaultValuesService->getInvoiceDefaultValues()->getPaymentType();
        }
        return $getPaymentType;
    }

    private function populateInvoiceItemEntity(InvoiceDataDto $invoiceDto, Invoice $invoiceEntity): void
    {
        foreach ($invoiceDto->getInvoiceItems() as $invoiceItemDto) {
            $invoiceItemEntity = $this->toInvoiceItemEntity($invoiceItemDto);
            $invoiceEntity->addInvoiceItemEntity($invoiceItemEntity);
        }
    }
}
