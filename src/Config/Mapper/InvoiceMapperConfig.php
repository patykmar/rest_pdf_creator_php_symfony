<?php

namespace App\Config\Mapper;

use App\Entity\Company;
use App\Entity\Invoice;
use App\Entity\InvoiceItem;
use App\Exceptions\InvalidArgumentException;
use App\Model\DataDto\InvoiceDataDto;
use App\Model\Dto\CompanyDto;
use App\Model\Dto\InvoiceDto;
use App\Model\Dto\InvoiceItemDto;
use App\Trait\MapperUtilsTrait;
use AutoMapperPlus\AutoMapperInterface;
use AutoMapperPlus\AutoMapperPlusBundle\AutoMapperConfiguratorInterface;
use AutoMapperPlus\Configuration\AutoMapperConfigInterface;
use AutoMapperPlus\MappingOperation\Operation;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;

class InvoiceMapperConfig implements AutoMapperConfiguratorInterface
{
    public const INVOICE_VS_LENGTH = 6;
    public const DUE_DAY = 14;
    public const CURRENCY = "CZK";
    public const KS = "0308";
    public const PAYMENT_TYPE = "transfer";

    use MapperUtilsTrait;

    public function configure(AutoMapperConfigInterface $config): void
    {
        $config->registerMapping(InvoiceDataDto::class, Invoice::class)
            ->forMember('paymentType', function (InvoiceDataDto $invoiceDataDto) {
                return $this->nullSafeSetPaymentType($invoiceDataDto->getPaymentType());
            })
            ->forMember('created', function (InvoiceDataDto $invoiceDataDto) {
                return $this->nullSafeSetCreatedTime($invoiceDataDto->getCreated());
            })
            ->forMember('dueDay', function (InvoiceDataDto $invoiceDataDto) {
                return $this->nullSafeDueDay($invoiceDataDto->getDueDay());
            })
            ->forMember('vs', function (InvoiceDataDto $invoiceDataDto) {
                return $this->nullSafeSetVs($invoiceDataDto->getVs());
            })
            ->forMember('ks', function (InvoiceDataDto $invoiceDataDto) {
                return $this->nullSafeSetKs($invoiceDataDto->getKs());
            })
            ->forMember('currency', function (InvoiceDataDto $invoiceDataDto) {
                return $this->nullSafeSetCurrency($invoiceDataDto->getCurrency());
            })
            ->forMember('supplier', Operation::mapTo(Company::class))
            ->forMember('subscriber', Operation::mapTo(Company::class))
            ->forMember('invoiceItems', function (InvoiceDataDto $invoiceDataDto, AutoMapperInterface $mapper) {
                return new ArrayCollection(
                    $mapper->mapMultiple($invoiceDataDto->getInvoiceItems(), InvoiceItem::class)
                );
            });

        $config->registerMapping(InvoiceDto::class, InvoiceDataDto::class)
            ->forMember('invoiceItems', function (InvoiceDto $invoiceDto, AutoMapperInterface $mapper) {
                return $mapper->mapMultiple($invoiceDto->getInvoiceItems(), InvoiceItemDto::class);
            });
        $config->registerMapping(InvoiceDto::class, Invoice::class)
            ->forMember('supplier', Operation::ignore())
            ->forMember('subscriber', Operation::ignore())
            ->forMember('created', function (InvoiceDto $invoiceDto) {
                return $this->unixTimeToDateTime($invoiceDto->getCreated());
            })
            ->forMember('invoiceItems', function (InvoiceDto $invoiceDto, AutoMapperInterface $mapper) {
                return new ArrayCollection(
                    $mapper->mapMultiple($invoiceDto->getInvoiceItems(), InvoiceItem::class)
                );
            });

        $config->registerMapping(Invoice::class, InvoiceDataDto::class)
            ->forMember('supplier', Operation::mapTo(CompanyDto::class))
            ->forMember('subscriber', Operation::mapTo(CompanyDto::class))
            ->forMember('created', function (Invoice $invoice) {
                return date_timestamp_get($invoice->getCreated());
            })
            ->forMember('invoiceItems', function (Invoice $invoice, AutoMapperInterface $mapper) {
                return $mapper->mapMultiple($invoice->getInvoiceItems(), InvoiceItemDto::class);
            });
    }

    private function nullSafeSetPaymentType(?string $getPaymentType): string
    {
        if (is_null($getPaymentType)) {
            return self::PAYMENT_TYPE;
        }
        return $getPaymentType;
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

    private function nullSafeDueDay(?int $dueDay): int
    {
        if (is_null($dueDay)) {
            return self::DUE_DAY;
        }
        return $dueDay;
    }

    /**
     * @param ?string $vs
     * @return string
     * @throws InvalidArgumentException
     */
    private function nullSafeSetVs(?string $vs): string
    {
        if (is_null($vs)) {
            $randomMaxValue = str_pad('9', self::INVOICE_VS_LENGTH, '9', STR_PAD_LEFT);
            return sprintf("%s%s",
                $this->getActualYear(),
                str_pad(rand(100, (int)$randomMaxValue), self::INVOICE_VS_LENGTH, "0", STR_PAD_LEFT)
            );
        }
        return $vs;
    }

    private function nullSafeSetKs(?string $ks): string
    {
        if (is_null($ks)) {
            return self::KS;
        }
        return $ks;
    }

    private function nullSafeSetCurrency(?string $currency): string
    {
        if (is_null($currency)) {
            return self::CURRENCY;
        }
        return strtoupper($currency);
    }

}
