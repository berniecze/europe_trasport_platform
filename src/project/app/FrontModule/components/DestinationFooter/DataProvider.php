<?php
declare(strict_types=1);

namespace App\FrontModule\Components\DestinationFooter;

use App\Model\Company\Company;
use App\Model\Company\CompanyRepository;
use App\Model\Destination\DestinationRepository;
use App\Model\Destination\Exceptions\DestinationNotFoundException;
use Kdyby\Doctrine\ResultSet;

class DataProvider
{

	/**
	 * @var DestinationRepository $destinationRepository
	 */
	private $destinationRepository;

	/**
	 * @var CompanyRepository $companyRepository
	 */
	private $companyRepository;

	public function __construct(DestinationRepository $destinationRepository, CompanyRepository $companyRepository)
	{
		$this->destinationRepository = $destinationRepository;
		$this->companyRepository = $companyRepository;
	}

	/**
	 * @return ResultSet
	 * @throws DestinationNotFoundException
	 */
	public function getAllDestinations(): ResultSet
	{
		return $this->destinationRepository->getAll();
	}

	public function getCompany(): ?Company
	{
		try {
			return $this->companyRepository->getCompany();
		} catch (\Exception $exception) {
			return null;
		}
	}
}
