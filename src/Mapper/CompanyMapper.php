<?php

namespace App\Mapper;

use App\Entity\Company;
use App\Entity\IEntity;
use App\Model\Dto\CompanyDto;
use App\Trait\MapperUtilsTrait;
use AutoMapperPlus\AutoMapperInterface;
use AutoMapperPlus\Exception\UnregisteredMappingException;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use ReflectionException;

class CompanyMapper implements ICrudMapper
{
    use MapperUtilsTrait;

    public function __construct(
        private readonly AutoMapperInterface $mapper
    )
    {
    }

    /**
     * @throws UnregisteredMappingException
     */
    private function toDtoStrict(Company $entity): CompanyDto
    {
        return $this->mapper->map($entity, CompanyDto::class);
    }

    /**
     * @psalm-param Collection<Company> $entities
     * @psalm-return ArrayCollection<CompanyDto>
     */
    public function toDtoCollection(Collection $entities): ArrayCollection
    {
        try {
            return $this->mappingCollection($entities, Company::class, 'toDto');
        } catch (ReflectionException $e) {
            return new ArrayCollection();
        }
    }

    /**
     * @param CompanyDto $dto
     * @throws UnregisteredMappingException
     */
    public function toEntity($dto): Company
    {
        return $this->mapper->map($dto, Company::class);
    }

    /**
     * @throws UnregisteredMappingException
     */
    public function toDto($entity): CompanyDto
    {
        return $this->toDtoStrict($entity);
    }

    /**
     * Mapping method before save editing
     */
    public function editItemMapper(IEntity $company, CompanyDto $companyDto): void
    {
        if ($company instanceof Company) {
            $company->setName($companyDto->getName());
            $company->setCountry($companyDto->getAddress()->getCountry());
            $company->setStreet($companyDto->getAddress()->getStreet());
            $company->setCity($companyDto->getAddress()->getCity());
            $company->setZipCode($companyDto->getAddress()->getZipCode());
            $company->setCompanyId($companyDto->getCompanyId());
            $company->setVatNumber($companyDto->getVatNumber());
            $company->setBankAccountNumber($companyDto->getBankAccountNumber());
            $company->setIban($companyDto->getIban());
            $company->setSwift($companyDto->getSwift());
            $company->setSignature($companyDto->getSignature());
        }
    }
}
