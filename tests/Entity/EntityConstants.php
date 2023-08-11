<?php

namespace App\Tests\Entity;

use App\Entity\Company;

class EntityConstants
{
    private array $companies;

    public function getCompanies(): array
    {
        return $this->companies;
    }

    private function populateCompanies(): void
    {
        $this->companies = [
            $this->createCompany(),
            $this->createCompany(2),
            $this->createCompany(3),
            $this->createCompany(4),
            $this->createCompany(5)
        ];
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
            ->setCompanyId("1234567890")
            ->setBankAccountNumber("1234-1234567890/1234")
            ->setIban("CZ0000123412345678901234")
            ->setSwift("AIRACZPP1234567890")
            ->setSignature("CZ0000123412345678901234");
        return $company;
    }

}