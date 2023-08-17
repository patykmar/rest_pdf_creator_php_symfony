<?php

namespace App\Mapper;

use App\Entity\Company;
use App\Model\Dto\CompanyDto;

class CompanyMapper
{
    public function __construct(
        private readonly AddressMapper $addressMapper
    )
    {
    }

    public function toEntity(CompanyDto $dto): Company
    {
        $company = new Company();
        $company->setName($dto->getName())
            ->setCountry($dto->getAddress()->getCountry())
            ->setStreet($dto->getAddress()->getStreet())
            ->setCity($dto->getAddress()->getCity())
            ->setZipCode($dto->getAddress()->getZipCode())
            ->setCompanyId($dto->getCompanyId())
            ->setVatNumber($dto->getVatNumber())
            ->setBankAccountNumber($dto->getBankAccountNumber())
            ->setIban($dto->getIban())
            ->setSwift($dto->getSwift())
            ->setSignature($dto->getSignature());
        return $company;
    }

    public function toDto(Company $entity): CompanyDto
    {
        $companyDto = new CompanyDto();

        $companyDto
            ->setId($entity->getId())
            ->setName($entity->getName())
            ->setIban($entity->getIban())
            ->setCompanyId($entity->getCompanyId())
            ->setVatNumber($entity->getVatNumber())
            ->setBankAccountNumber($entity->getBankAccountNumber())
            ->setIban($entity->getIban())
            ->setSwift($entity->getSwift())
            ->setSignature($entity->getSignature())
            ->setAddress($this->addressMapper->toDto($entity));
        return $companyDto;
    }
}
