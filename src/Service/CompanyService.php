<?php

namespace App\Service;

use App\Entity\Company;
use App\Mapper\CompanyMapper;
use App\Model\Dto\CompanyDto;
use App\Repository\CompanyRepository;
use AutoMapperPlus\Exception\UnregisteredMappingException;

/**
 * @template-extends AbstractCrudService<Company, CompanyDto, CompanyMapper, CompanyRepository>
 *
 * @method Company    getOneEntity(int $id)
 * @method CompanyDto getOneDto(int $id)
 */
class CompanyService extends AbstractCrudService
{
    public function __construct(
        private readonly CompanyMapper     $companyMapper,
        private readonly CompanyRepository $companyRepository
    )
    {
        parent::__construct($this->companyMapper, $this->companyRepository);
    }

    /**
     * @param CompanyDto $dto
     * @throws UnregisteredMappingException
     */
    public function saveEntity($dto): CompanyDto
    {
        return $this->saveEntityStrict($dto);
    }

    /**
     * @throws UnregisteredMappingException
     */
    private function saveEntityStrict(CompanyDto $dto): CompanyDto
    {
        $this->companyRepository->save($this->companyMapper->toEntity($dto));
        $criteria = [
            'name' => $dto->getName(),
            'country' => $dto->getAddress()->getCountry(),
            'street' => $dto->getAddress()->getStreet(),
            'city' => $dto->getAddress()->getCity(),
            'zipCode' => $dto->getAddress()->getZipCode(),
            'companyId' => $dto->getCompanyId(),
            'vatNumber' => $dto->getVatNumber(),
            'bankAccountNumber' => $dto->getBankAccountNumber(),
            'iban' => $dto->getIban(),
            'swift' => $dto->getSwift(),
        ];
        $company = $this->companyRepository->findOneBy($criteria);
        return $this->companyMapper->toDto($company);
    }

    /**
     * @param CompanyDto $dto
     * @param int $id
     * @return CompanyDto
     * @throws UnregisteredMappingException
     */
    public function editEntity($dto, int $id): CompanyDto
    {
        $this->checkId($id);
        $this->companyMapper->editItemMapper($this->entity, $dto);
        $this->companyRepository->save($this->entity);
        return $this->companyMapper->toDto($this->entity);
    }

}
