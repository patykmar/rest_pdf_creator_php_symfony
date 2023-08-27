<?php

namespace App\Tests\Mock\Entity;

use App\Entity\Company;
use Doctrine\Common\Collections\ArrayCollection;

class EntityMock
{
    public const COMPANIES_COUNT = 20;
    /** @var ArrayCollection<Company> $companies */
    private ArrayCollection $companies;

    public function __construct()
    {
        $this->companies = new ArrayCollection();
        $this->populateCompanies();
    }

    /**
     * @psalm-return ArrayCollection<Company>
    */
    public function getCompanies(): ArrayCollection
    {
        return $this->companies;
    }

    private function populateCompanies(): void
    {
        for ($i = 1; $i <= self::COMPANIES_COUNT; $i++) {
            $this->companies->add($this->createCompany($i));
        }
    }

    public function createCompany(int $id = 1): Company
    {
        $company = new Company();
        $company
            ->setName("Company $id name ")
            ->setCountry("Company $id country name")
            ->setStreet("Company $id street name")
            ->setCity("Company $id city name")
            ->setZipCode("Company $id zip code")
            ->setCompanyId($id . "234567890")
            ->setBankAccountNumber($id . "234-1234567890/1234")
            ->setIban("CZ000012341234567890123" . $id)
            ->setSwift("AIRACZPP123456789" . $id)
            ->setSignature("CZ0000123412345678901234");
        return $company;
    }

}