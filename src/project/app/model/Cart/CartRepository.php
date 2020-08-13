<?php
declare(strict_types=1);

namespace App\Model\Cart;

use App\Model\BaseRepository;
use App\Model\Cart\Exceptions\CartNotFoundException;
use DateTimeInterface;
use Kdyby\Doctrine\ResultSet;

class CartRepository extends BaseRepository
{

	protected function getEntityName(): string
	{
		return Cart::class;
	}

	public function getAll(): ResultSet
	{
		$query = new CartQuery();

		$data = $this->repository->fetch($query);

		if ($data->getTotalCount() === 0) {
			throw new CartNotFoundException;
		}

		return $data;
	}

	/**
	 * @param string $cartHash
	 *
	 * @return Cart
	 * @throws CartNotFoundException
	 */
	public function getByHash(string $cartHash): Cart
	{
		$query = (new CartQuery())->byHash($cartHash);
		$res = $this->repository->fetchOne($query);

		if ($res === null) {
			throw CartNotFoundException::byHash($cartHash);
		}
		return $res;
	}

	/**
	 * @param DateTimeInterface $date
	 *
	 * @return ResultSet
	 * @throws CartNotFoundException
	 */
	public function getByTransferDate(DateTimeInterface $date): ResultSet
	{
		$query = (new CartQuery())
			->byCartDate($date);

		$data = $this->repository->fetch($query);

		if ($data->getTotalCount() === 0) {
			throw new CartNotFoundException();
		}
		return $data;
	}
}
