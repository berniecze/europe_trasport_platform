<?php
declare(strict_types=1);

namespace App\Model\Destination;

use App\Model\BaseRepository;
use App\Model\Destination\Exceptions\DestinationNotFoundException;
use Kdyby\Doctrine\ResultSet;

class DestinationRepository extends BaseRepository
{

	protected function getEntityName(): string
	{
		return Destination::class;
	}

	/**
	 * @return ResultSet
	 * @throws DestinationNotFoundException
	 */
	public function getAll(): ResultSet
	{
		$query = (new DestinationQuery());

		$data = $this->repository->fetch($query);

		if ($data->getTotalCount() === 0) {
			throw new DestinationNotFoundException;
		}

		return $data;
	}

	/**
	 * @return ResultSet
	 * @throws DestinationNotFoundException
	 */
	public function getOrderedByCountryForSelect(): ResultSet
	{
		$query = (new DestinationQuery())->orderedByCountry();
		$data = $this->repository->fetch($query);

		if ($data->getTotalCount() === 0) {
			throw new DestinationNotFoundException;
		}

		return $data;
	}

	/**
	 * @param int $id
	 *
	 * @return Destination
	 * @throws DestinationNotFoundException
	 */
	public function getById(int $id): Destination
	{
		/** @var Destination $entity */
		$entity = $this->repository->find($id);

		if (!$entity) {
			throw DestinationNotFoundException::byPrimaryKey($id);
		}
		return $entity;
	}

	/**
	 * @param string $name
	 *
	 * @return Destination
	 * @throws DestinationNotFoundException
	 */
	public function getByName(string $name): Destination
	{
		$query = (new DestinationQuery())->byName($name);

		$data = $this->repository->fetchOne($query);

		/** @var Destination|null $data */
		if ($data === null) {
			throw new DestinationNotFoundException();
		}

		return $data;
	}

	/**
	 * @param int[] $ids
	 *
	 * @return ResultSet
	 * @throws DestinationNotFoundException
	 */
	public function getByIds(array $ids): ResultSet
	{
		$query = (new DestinationQuery())->byIds($ids);

		$data = $this->repository->fetch($query);

		if ($data->getTotalCount() === 0) {
			throw new DestinationNotFoundException();
		}

		return $data;
	}
}
