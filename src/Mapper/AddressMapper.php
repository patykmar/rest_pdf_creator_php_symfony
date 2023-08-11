<?php

namespace App\Mapper;

use App\Entity\Company;
use App\Model\Dto\AddressDto;

class AddressMapper
{
    public function toDto(Company $company): AddressDto
    {
        $addressDto = new AddressDto();
        return $addressDto
            ->setCity($company->getCity())
            ->setStreet($company->getStreet())
            ->setCountry($company->getCountry())
            ->setZipCode($company->getZipCode());
    }
}
