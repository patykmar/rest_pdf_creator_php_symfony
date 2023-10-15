<?php

namespace App\Config\Mapper;

use App\Entity\Company;
use App\Model\Dto\CompanyDto;
use AutoMapperPlus\AutoMapperPlusBundle\AutoMapperConfiguratorInterface;
use AutoMapperPlus\Configuration\AutoMapperConfigInterface;

class CompanyMapperConfig implements AutoMapperConfiguratorInterface
{
    public function configure(AutoMapperConfigInterface $config): void
    {
        $config->registerMapping(CompanyDto::class, Company::class)
            ->forMember('country', function (CompanyDto $companyDto) {
                return $companyDto->getAddress()->getCountry();
            })
            ->forMember('street', function (CompanyDto $companyDto) {
                return $companyDto->getAddress()->getStreet();
            })
            ->forMember('city', function (CompanyDto $companyDto) {
                return $companyDto->getAddress()->getCity();
            })
            ->forMember('zipCode', function (CompanyDto $companyDto) {
                return $companyDto->getAddress()->getZipCode();
            });
    }
}
