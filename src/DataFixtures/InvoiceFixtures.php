<?php

namespace App\DataFixtures;

use App\Config\Mapper\InvoiceMapperConfig;
use App\Entity\Company;
use App\Entity\Invoice;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class InvoiceFixtures extends Fixture implements DependentFixtureInterface
{
    public const REFERENCE_COUNT = 10;
    public const INVOICE_01 = 'invoice-01';
    public const INVOICE_02 = 'invoice-02';
    public const INVOICE_03 = 'invoice-03';
    public const INVOICE_04 = 'invoice-04';
    public const INVOICE_05 = 'invoice-05';
    public const INVOICE_06 = 'invoice-06';
    public const INVOICE_07 = 'invoice-07';
    public const INVOICE_08 = 'invoice-08';
    public const INVOICE_09 = 'invoice-09';
    public const INVOICE_10 = 'invoice-10';
    

    public static function getReferences(): array
    {
        return [
            self::INVOICE_01, self::INVOICE_02, self::INVOICE_03, self::INVOICE_04, self::INVOICE_05,
            self::INVOICE_06, self::INVOICE_07, self::INVOICE_08, self::INVOICE_09, self::INVOICE_10,
        ];
    }

    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i <= self::REFERENCE_COUNT; $i++) {
            $modulo = $i % (CompanyFixtures::REFERENCE_COUNT - 1);

            $subscriber = $this->getReference(CompanyFixtures::getReferences()[$modulo]);
            $supplier = $this->getReference(CompanyFixtures::getReferences()[$modulo + 1]);

            $invoice = InvoiceFixtures::createEntity($i, $subscriber, $supplier);
            $this->setReference(self::getReferences()[$i - 1], $invoice);
            $manager->persist($invoice);
            unset($invoice);
        }
        $manager->flush();
    }

    public static function createEntity(int $id, Company $subscriber, Company $supplier): Invoice
    {
        $invoice = new Invoice();
        $invoice
            ->setId($id)
            ->setDescription("Invoice description " . $id)
            ->setPaymentType(InvoiceMapperConfig::PAYMENT_TYPE)
            ->setCreated(new DateTime())
            ->setDueDay(InvoiceMapperConfig::DUE_DAY)
            ->setVs(date("Y") . "000" . $id)
            ->setKs(InvoiceMapperConfig::KS)
            ->setCurrency(InvoiceMapperConfig::CURRENCY)
            ->setSubscriber($subscriber)
            ->setSupplier($supplier);
        return $invoice;
    }

    public function getDependencies(): array
    {
        return [CompanyFixtures::class];
    }
}
