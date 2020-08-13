<?php
declare(strict_types=1);

namespace App\Model\Country;

use App\Model\BaseRepository;
use App\Model\Country\Exceptions\CountryNotFoundException;
use Kdyby\Doctrine\ResultSet;

class CountryRepository extends BaseRepository
{

	protected function getEntityName(): string
	{
		return Country::class;
	}

	/**
	 * @return ResultSet
	 * @throws CountryNotFoundException
	 */
	public function getAll(): ResultSet
	{
		$query = (new CountryQuery());

		$data = $this->repository->fetch($query);

		if ($data->getTotalCount() === 0) {
			throw new CountryNotFoundException;
		}

		return $data;
	}

	/**
	 * @param int $id
	 *
	 * @return Country
	 * @throws CountryNotFoundException
	 */
	public function getById(int $id): Country
	{
		/** @var Country $entity */
		$entity = $this->repository->find($id);

		if (!$entity) {
			throw CountryNotFoundException::byId($id);
		}
		return $entity;
	}

}
