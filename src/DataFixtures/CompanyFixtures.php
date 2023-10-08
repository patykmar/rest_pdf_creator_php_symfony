<?php

namespace App\DataFixtures;

use App\Entity\Company;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CompanyFixtures extends Fixture
{
    public const REFERENCE_COUNT = 10;
    public const COMPANY_01 = 'Company-1';
    public const COMPANY_02 = 'Company-2';
    public const COMPANY_03 = 'Company-3';
    public const COMPANY_04 = 'Company-4';
    public const COMPANY_05 = 'Company-5';
    public const COMPANY_06 = 'Company-6';
    public const COMPANY_07 = 'Company-7';
    public const COMPANY_08 = 'Company-8';
    public const COMPANY_09 = 'Company-9';
    public const COMPANY_10 = 'Company-10';

    private function getReferences(): array
    {
        return [
            self::COMPANY_01, self::COMPANY_02, self::COMPANY_03, self::COMPANY_04, self::COMPANY_05,
            self::COMPANY_06, self::COMPANY_07, self::COMPANY_08, self::COMPANY_09, self::COMPANY_10,
        ];
    }

    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < self::REFERENCE_COUNT; $i++) {
            $company = self::createCompany($i + 1);
            $this->setReference($this->getReferences()[$i], $company);
            $manager->persist($company);
            unset($company);
        }
        $manager->flush();
    }

    public static function createCompany(int $id = 1): Company
    {
        $company = new Company();
        $company
            ->setName("Company $id name ")
            ->setCountry("Company $id country name")
            ->setStreet("Company $id street name")
            ->setCity("Company $id city name")
            ->setZipCode("Company $id zip code")
            ->setCompanyId("234567890" . $id)
            ->setVatNumber("123456789" . $id)
            ->setBankAccountNumber($id . "234-1234567890/1234")
            ->setIban("CZ000012341234567890123" . $id)
            ->setSwift("AIRACZPP123456789" . $id)
            ->setSignature("CZ0000123412345678901234" . $id);
        return $company;
    }
}
