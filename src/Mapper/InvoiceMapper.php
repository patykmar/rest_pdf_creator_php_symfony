<?php

namespace App\Mapper;

use App\Entity\InvoiceEntity;
use App\Entity\InvoiceItemEntity;
use App\Exceptions\InvalidArgumentException;
use App\Model\Dto\InvoiceDto;
use App\Model\Dto\InvoiceItemDto;
use App\Service\InvoiceDefaultValuesService;
use DateTimeInterface;

class InvoiceMapper
{
    use MapperUtilsTrait;

    public function __construct(
        private readonly InvoiceDefaultValuesService $defaultValuesService
    )
    {
    }

    public function toInvoiceEntity(InvoiceDto $invoiceDto): InvoiceEntity
    {
        $invoiceEntity = new InvoiceEntity();
        $this->populateInvoiceItemEntity($invoiceDto, $invoiceEntity);
        return $invoiceEntity
            ->setPaymentType($invoiceDto->getPaymentType())
            ->setCreated($this->nullSafeSetCreatedTime($invoiceDto->getCreated()))
            ->setDueDay($this->nullSafeDueDay($invoiceDto->getDueDay()))
            ->setVs($this->nullSafeSetVs($invoiceDto->getVs()))
            ->setKs($this->nullSafeSetKs($invoiceDto->getKs()))
            ->setCurrency($this->nullSafeSetCurrency($invoiceDto->getCurrency()))
            ->setSupplierName($invoiceDto->getSupplier()->getName())
            ->setSupplierCompanyId($invoiceDto->getSupplier()->getCompanyId())
            ->setSupplierVatNumber($invoiceDto->getSupplier()->getVatNumber())
            ->setSupplierBankAccountNumber($invoiceDto->getSupplier()->getBankAccountNumber())
            ->setSupplierSwift($invoiceDto->getSupplier()->getSwift())
            ->setSupplierAddressCountry($invoiceDto->getSupplier()->getAddress()->getCountry())
            ->setSupplierAddressStreet($invoiceDto->getSupplier()->getAddress()->getStreet())
            ->setSupplierAddressCity($invoiceDto->getSupplier()->getAddress()->getCity())
            ->setSupplierAddressZipCode($invoiceDto->getSupplier()->getAddress()->getZipCode())
            ->setSubscriberName($invoiceDto->getSubscriber()->getName())
            ->setSubscriberCompanyId($invoiceDto->getSubscriber()->getCompanyId())
            ->setSubscriberVatNumber($invoiceDto->getSubscriber()->getVatNumber())
            ->setSubscriberBankAccountNumber($invoiceDto->getSubscriber()->getBankAccountNumber())
            ->setSubscriberSwift($invoiceDto->getSubscriber()->getSwift())
            ->setSubscriberAddressCountry($invoiceDto->getSubscriber()->getAddress()->getCountry())
            ->setSubscriberAddressStreet($invoiceDto->getSubscriber()->getAddress()->getStreet())
            ->setSubscriberAddressCity($invoiceDto->getSubscriber()->getAddress()->getCity())
            ->setSubscriberAddressZipCode($invoiceDto->getSubscriber()->getAddress()->getZipCode());
    }

    public function toInvoiceItemEntity(InvoiceItemDto $invoiceItemDto): InvoiceItemEntity
    {
        $invoiceItemEntity = new InvoiceItemEntity();
        return $invoiceItemEntity
            ->setVat($invoiceItemDto->getVat())
            ->setItemName($invoiceItemDto->getItemName())
            ->setPrice($invoiceItemDto->getPrice())
            ->setUnitCount($invoiceItemDto->getUnitCount());
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

    private function populateInvoiceItemEntity(InvoiceDto $invoiceDto, InvoiceEntity $invoiceEntity): void
    {
        foreach ($invoiceDto->getInvoiceItems() as $invoiceItemDto) {
            $invoiceItemEntity = $this->toInvoiceItemEntity($invoiceItemDto);
            $invoiceEntity->addInvoiceItemEntity($invoiceItemEntity);
        }
    }
}
