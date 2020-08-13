<?php
declare(strict_types=1);

namespace App\AdminModule\Components\OrderList;

use App\Model\ClientOrder\ClientOrderRepository;
use App\Model\ClientOrder\Exceptions\ClientOrderNotFoundException;
use Kdyby\Doctrine\ResultSet;

class DataProvider
{

	/**
	 * @var ClientOrderRepository
	 */
	private $clientOrderRepository;

	public function __construct(ClientOrderRepository $clientOrderRepository)
	{
		$this->clientOrderRepository = $clientOrderRepository;
	}

	/**
	 * @return ResultSet
	 * @throws ClientOrderNotFoundException
	 */
	public function getAcceptedOrders(): ResultSet
	{
		return $this->clientOrderRepository->getAcceptedOnly();
	}

	/**
	 * @return ResultSet
	 * @throws ClientOrderNotFoundException
	 */
	public function getNotAcceptedOrders(): ResultSet
	{
		return $this->clientOrderRepository->getNotAcceptedOnly();
	}

	/**
	 * @return ResultSet
	 * @throws ClientOrderNotFoundException
	 */
	public function getAllOrders(): ResultSet
	{
		return $this->clientOrderRepository->getAll();
	}
}
