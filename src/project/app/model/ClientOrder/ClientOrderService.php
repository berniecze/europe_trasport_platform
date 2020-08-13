<?php
declare(strict_types=1);

namespace App\Model\ClientOrder;

use App\Model\Driver\Driver;
use Exception;
use Kdyby\Doctrine\EntityManager;

class ClientOrderService
{

	/**
	 * @var EntityManager  $em
	 */
	private $em;

	public function __construct(EntityManager $em)
	{
		$this->em = $em;
	}

	/**
	 * @param ClientOrder $clientOrder
	 *
	 * @throws Exception
	 */
	public function save(ClientOrder $clientOrder): void
	{
		$this->em->persist($clientOrder);
		$this->em->flush($clientOrder);
	}

	/**
	 * @param Driver $driver
	 * @param ClientOrder $order
	 *
	 * @throws Exception
	 */
	public function assignDriverToOrder(Driver $driver, ClientOrder $order)
	{
		$order->setDriver($driver);
		$this->save($order);
	}
}
