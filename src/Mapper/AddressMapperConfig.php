<?php

namespace App\Mapper;

use App\Entity\Company;
use App\Model\Dto\AddressDto;
use AutoMapperPlus\AutoMapperPlusBundle\AutoMapperConfiguratorInterface;
use AutoMapperPlus\Configuration\AutoMapperConfigInterface;

class AddressMapperConfig implements AutoMapperConfiguratorInterface
{
    public function configure(AutoMapperConfigInterface $config): void
    {
        $config->registerMapping(Company::class, AddressDto::class);
    }
}
