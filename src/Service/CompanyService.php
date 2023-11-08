<?php

namespace App\Service;

use App\Entity\Company;
use App\Exceptions\InvalidArgumentException;
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
    const NOT_FOUND_ERROR_MSG = "Company with id: %d not found";

    public function __construct(
        private readonly CompanyMapper     $companyMapper,
        private readonly CompanyRepository $companyRepository
    )
    {
        parent::__construct($this->companyMapper, $this->companyRepository, self::NOT_FOUND_ERROR_MSG);
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
        $company = $this->companyRepository->findLastEntity();
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
        $entityEdit = $this->companyMapper->mappingBeforeEditEntity(
            $this->entity, $this->mapper->toEntity($dto)
        );
        $this->companyRepository->save($entityEdit);
        return $this->companyMapper->toDto($entityEdit);
    }

    /**
     * Find two companies by their ID and return it as associate array
     * @param int $id1 of company
     * @param int $id2 of company
     * @return Company[] Map<int, Company>
     */
    public function findTwoCompaniesByIds(int $id1, int $id2): array
    {
        $companies = $this->companyRepository->findTwoCompanies($id1, $id2);
        if (!is_array($companies) || count($companies) != 2) {
            throw new InvalidArgumentException("Companies by IDs is not found");
        }
        return $this->companyMapper->mappingArrayOfCompaniesToMap($companies);
    }

}
