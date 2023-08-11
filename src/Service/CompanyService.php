<?php

namespace App\Service;

use App\Entity\Company;
use App\Exceptions\NotFoundException;
use App\Mapper\CompanyMapper;
use App\Model\Dto\CompanyDto;
use App\Repository\CompanyRepository;

/**
 * @implements CrudServiceInterface<Company,CompanyDto>
 */
class CompanyService implements CrudServiceInterface
{
    private Company $company;

    public function __construct(
        private readonly CompanyMapper     $companyMapper,
        private readonly CompanyRepository $companyRepository
    )
    {
    }

    public function getOneEntity(int $id): Company
    {
        $this->checkId($id);
        return $this->company;
    }

    public function getOneDto(int $id): CompanyDto
    {
        return $this->mapEntityToDto($this->getOneEntity($id));
    }

    public function mapEntityToDto(Company $company): CompanyDto
    {
        return $this->companyMapper->toDto($company);
    }

    public function mapDtoToEntity(CompanyDto $companyDto): Company
    {
        return $this->companyMapper->toEntity($companyDto);
    }

    /**
     * @param CompanyDto $dto
     * @return CompanyDto
     */
    public function saveEntity($dto): CompanyDto
    {
        $this->companyRepository->save($this->mapDtoToEntity($dto));
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
        return $this->mapEntityToDto($company);
    }

    /**
     * @param CompanyDto $dto
     * @param int $id
     * @return CompanyDto
     */
    public function editEntity($dto, int $id): CompanyDto
    {
        $this->checkId($id);
        $this->company = $this->mapDtoToEntity($dto);
        $this->company->setId($id);
        $this->companyRepository->save($this->company);
        return $this->mapEntityToDto($this->company);
    }

    public function deleteEntity(int $id): void
    {
        $this->checkId($id);
        $this->companyRepository->remove($this->company, true);
    }

    /**
     * @throws NotFoundException
     */
    private function checkId(int $id): void
    {
        $this->company = $this->companyRepository->find($id);
        if (is_null($this->company)) {
            throw new NotFoundException(sprintf("Company with id: %d not found", $id));
        }
    }
}
