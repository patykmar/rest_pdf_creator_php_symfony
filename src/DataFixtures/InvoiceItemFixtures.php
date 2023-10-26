<?php

namespace App\DataFixtures;

use App\Entity\Invoice;
use App\Entity\InvoiceItem;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class InvoiceItemFixtures extends Fixture implements DependentFixtureInterface
{
    const REFERENCE_COUNT = 10;

    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < count(InvoiceFixtures::getReferences()); $i++) {
            $invoice = $this->getReference(InvoiceFixtures::getReferences()[$i]);
            $this->populateEntity($manager, $invoice);
            unset($invoice);
        }
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [InvoiceFixtures::class];
    }

    public function populateEntity(ObjectManager $manager, Invoice $invoice): void
    {
        for ($i = 1; $i <= self::REFERENCE_COUNT; $i++) {
            $invoiceItem = self::creatEntity($i, $invoice);
            $manager->persist($invoiceItem);
            unset($invoiceItem);
        }
    }

    public static function creatEntity(int $id, Invoice $invoice): InvoiceItem
    {
        $invoiceItem = new InvoiceItem();
        return $invoiceItem
            ->setId($id)
            ->setInvoice($invoice)
            ->setVat(0)
            ->setItemName("Invoice item 0" . $id)
            ->setPrice(10000.0 + ($id / 10) + ($id * 10))
            ->setUnitCount(1.0 * $id * 10);
    }
}
