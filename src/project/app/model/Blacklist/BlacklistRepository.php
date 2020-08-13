<?php
declare(strict_types=1);

namespace App\Model\Blacklist;

use App\Model\BaseRepository;
use App\Model\Blacklist\Exceptions\BlacklistNotFoundException;
use App\Model\Transport\Transport;
use DateTimeImmutable;
use Kdyby\Doctrine\ResultSet;

class BlacklistRepository extends BaseRepository
{

	protected function getEntityName(): string
	{
		return Blacklist::class;
	}

	/**
	 * @return ResultSet
	 * @throws BlacklistNotFoundException
	 */
	public function getAll(): ResultSet
	{
		$query = new BlacklistQuery();

		$data = $this->repository->fetch($query);

		if ($data->getTotalCount() === 0) {
			throw new BlacklistNotFoundException();
		}

		return $data;
	}

	/**
	 * @param Transport $transport
	 *
	 * @return ResultSet
	 * @throws BlacklistNotFoundException
	 */
	public function getByTransport(Transport $transport): ResultSet
	{
		$query = (new BlacklistQuery())->byTransport($transport);

		$data = $this->repository->fetch($query);

		if ($data->getTotalCount() === 0) {
			throw new BlacklistNotFoundException();
		}

		return $data;
	}

	/**
	 * @param DateTimeImmutable $dateTime
	 *
	 * @return ResultSet
	 * @throws BlacklistNotFoundException
	 */
	public function getByDate(DateTimeImmutable $dateTime): ResultSet
	{
		$query = (new BlacklistQuery())->byDate($dateTime);

		$data = $this->repository->fetch($query);

		if ($data->getTotalCount() === 0) {
			throw new BlacklistNotFoundException();
		}

		return $data;
	}
}
