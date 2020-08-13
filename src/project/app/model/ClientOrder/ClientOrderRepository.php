<?php
declare(strict_types=1);

namespace App\Model\ClientOrder;

use App\Model\BaseRepository;
use App\Model\ClientOrder\Exceptions\ClientOrderNotFoundException;
use DateTimeInterface;
use Kdyby\Doctrine\ResultSet;

class ClientOrderRepository extends BaseRepository
{
	protected function getEntityName(): string
	{
		return ClientOrder::class;
	}

	public function getAll(): ResultSet
	{
		$query = new ClientOrderQuery();

		$data = $this->repository->fetch($query);

		if ($data->getTotalCount() === 0) {
			throw new ClientOrderNotFoundException();
		}

		return $data;
	}

	/**
	 * @return ClientOrder
	 * @throws ClientOrderNotFoundException
	 */
	public function getByHighestId(): ClientOrder
	{
		$query = (new ClientOrderQuery())->orderById();
		$res = $this->repository->fetchOne($query);

		if ($res === null) {
			throw new ClientOrderNotFoundException();
		}
		return $res;
	}

	/**
	 * @param int $id
	 *
	 * @return ClientOrder|null
	 */
	public function getById(int $id): ?ClientOrder
	{
		return $this->repository->find($id);
	}

	/**
	 * @return ResultSet
	 * @throws ClientOrderNotFoundException
	 */
	public function getAcceptedOnly(): ResultSet
	{
		$query = (new ClientOrderQuery())->onlyAccepted();

		$data = $this->repository->fetch($query);

		if ($data->getTotalCount() === 0) {
			throw new ClientOrderNotFoundException();
		}

		return $data;
	}

	/**
	 * @return ResultSet
	 * @throws ClientOrderNotFoundException
	 */
	public function getNotAcceptedOnly(): ResultSet
	{
		$query = (new ClientOrderQuery())->onlyNotAccepted();

		$data = $this->repository->fetch($query);

		if ($data->getTotalCount() === 0) {
			throw new ClientOrderNotFoundException();
		}

		return $data;
	}

	/**
	 * @param DateTimeInterface $date
	 *
	 * @return ResultSet
	 * @throws ClientOrderNotFoundException
	 */
	public function getFinishedByDate(DateTimeInterface $date): ResultSet
	{
		$query = (new ClientOrderQuery())
			->byTransportDate($date)
			->onlyFinished();

		$data = $this->repository->fetch($query);

		if ($data->getTotalCount() === 0) {
			throw new ClientOrderNotFoundException();
		}

		return $data;
	}

	/**
	 * @param DateTimeInterface $date
	 *
	 * @return array|ResultSet
	 * @throws ClientOrderNotFoundException
	 */
	public function notAcceptedOrderedByDate(DateTimeInterface $date)
	{
		$query = (new ClientOrderQuery())
			->byTransportDate($date)
			->onlyNotAccepted();

		$data = $this->repository->fetch($query);
		if ($data->getTotalCount() === 0) {
			throw new ClientOrderNotFoundException();
		}

		return $data;
	}

	/**
	 * @param DateTimeInterface $date
	 *
	 * @return array|ResultSet
	 * @throws ClientOrderNotFoundException
	 */
	public function acceptedOrderedByDate(DateTimeInterface $date)
	{
		$query = (new ClientOrderQuery())
			->byTransportDate($date)
			->onlyAccepted();

		$data = $this->repository->fetch($query);
		if ($data->getTotalCount() === 0) {
			throw new ClientOrderNotFoundException();
		}

		return $data;
	}
}
