<?php
declare(strict_types=1);

namespace App\Model\Company;

use App\Model\BaseRepository;
use App\Model\Company\Exceptions\CompanyNotFoundException;

class CompanyRepository extends BaseRepository
{

	protected function getEntityName(): string
	{
		return Company::class;
	}

	/**
	 * @return Company
	 * @throws CompanyNotFoundException
	 */
	public function getCompany(): Company
	{
		$query = new CompanyQuery();

		$data = $this->repository->fetchOne($query);

		if ($data === null) {
			throw new CompanyNotFoundException();
		}

		return $data;
	}

	/**
	 * @param int $id
	 *
	 * @return Company
	 * @throws CompanyNotFoundException
	 */
	public function getById(int $id): Company
	{
		/** @var Company|null $entity */
		$entity = $this->repository->find($id);

		if (!$entity) {
			throw CompanyNotFoundException::byId($id);
		}
		return $entity;
	}
}
