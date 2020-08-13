<?php
declare(strict_types=1);

namespace App\Model\Transport;

use App\Model\BaseRepository;
use App\Model\Transport\Exceptions\TransportNotFoundException;
use Kdyby\Doctrine\ResultSet;

class TransportRepository extends BaseRepository
{

	protected function getEntityName(): string
	{
		return Transport::class;
	}

	public function getAll(): ResultSet
	{
		$data = $this->repository->fetch(new TransportQuery());
		if ($data->getTotalCount() === 0) {
			throw new TransportNotFoundException;
		}

		return $data;
	}

	/**
	 * @param int $id
	 *
	 * @return Transport
	 * @throws TransportNotFoundException
	 */
	public function getById(int $id): Transport
	{
		/** @var Transport $entity */
		$entity = $this->repository->find($id);

		if (!$entity) {
			throw TransportNotFoundException::byPrimaryKey($id);
		}
		return $entity;
	}

	/**
	 * @param int[] $ids
	 *
	 * @return ResultSet
	 * @throws TransportNotFoundException
	 */
	public function getExcluded(array $ids): ResultSet
	{
		$query = (new TransportQuery())->byExcluded($ids);
		$data = $this->repository->fetch($query);

		if ($data->getTotalCount() === 0) {
			throw new TransportNotFoundException;
		}

		return $data;
	}
}
