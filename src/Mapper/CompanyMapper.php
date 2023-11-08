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
     * @psalm-param Collection<Company> $entities
     * @psalm-return Collection<CompanyDto>
     */
    public function toDtoCollection(Collection $entities): Collection
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
        return $this->mapper->map($entity, CompanyDto::class);
    }

    /**
     * @param IEntity|Company $entityFromDb
     * @param Company $entityFromConsumer
     * @return Company
     */
    public function mappingBeforeEditEntity($entityFromDb, $entityFromConsumer): Company
    {
        return $entityFromDb
            ->setName($entityFromConsumer->getName())
            ->setCountry($entityFromConsumer->getCountry())
            ->setStreet($entityFromConsumer->getStreet())
            ->setCity($entityFromConsumer->getCity())
            ->setZipCode($entityFromConsumer->getZipCode())
            ->setCompanyId($entityFromConsumer->getCompanyId())
            ->setVatNumber($entityFromConsumer->getVatNumber())
            ->setBankAccountNumber($entityFromConsumer->getBankAccountNumber())
            ->setIban($entityFromConsumer->getIban())
            ->setSwift($entityFromConsumer->getSwift())
            ->setSignature($entityFromConsumer->getSignature());
    }

    /**
     * This mapping method restructured List<Company>
     * to Map<int, Company> where key is ID of company
     * @param Company[] $companies
     * @return Company[] [id => Company]
     */
    public function mappingArrayOfCompaniesToMap(array $companies): array
    {
        $result = array();
        foreach ($companies as $company) {
            $result[$company->getId()] = $company;
        }
        return $result;
    }
}
