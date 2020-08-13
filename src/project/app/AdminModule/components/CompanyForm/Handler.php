<?php
declare(strict_types=1);

namespace App\AdminModule\Components\CompanyForm;

use App\Model\Company\CompanyRepository;
use App\Model\Company\CompanyService;
use App\Model\Company\Exceptions\CompanyNotFoundException;

class Handler
{

	/**
	 * @var CompanyRepository $companyRepository
	 */
	private $companyRepository;

	/**
	 * @var CompanyService $companyService
	 */
	private $companyService;

	public function __construct(CompanyRepository $companyRepository, CompanyService $companyService)
	{
		$this->companyRepository = $companyRepository;
		$this->companyService = $companyService;
	}

	/**
	 * @param CompanyRequest $companyRequest
	 *
	 * @throws CompanyNotFoundException
	 * @throws \Exception
	 */
	public function saveCompany(CompanyRequest $companyRequest)
	{
		$company = $this->companyRepository->getById($companyRequest->getId());
		$company->setName($companyRequest->getName());
		$company->setAddress($companyRequest->getAddress());
		$company->setIdentificationNumber($companyRequest->getIdentificationNumber());
		$company->setTax($companyRequest->getTax());

		$this->companyService->save($company);
	}
}
