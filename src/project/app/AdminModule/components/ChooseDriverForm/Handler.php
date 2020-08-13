<?php
declare(strict_types=1);

namespace App\AdminModule\Components\ChooseDriverForm;

use App\Model\ClientOrder\ClientOrderRepository;
use App\Model\ClientOrder\ClientOrderService;
use App\Model\Driver\Driver;
use App\Model\Driver\DriverService;
use Exception;
use Kdyby\Doctrine\EntityManager;

class Handler
{

	/**
	 * @var DriverService
	 */
	private $driverService;

	/**
	 * @var ClientOrderService
	 */
	private $clientOrderService;

	/**
	 * @var ClientOrderRepository
	 */
	private $clientOrderRepository;

	/**
	 * @var EntityManager
	 */
	private $entityManager;

	public function __construct(
		DriverService $driverService,
		ClientOrderService $clientOrderService,
		ClientOrderRepository $clientOrderRepository,
		EntityManager $entityManager
	) {
		$this->clientOrderRepository = $clientOrderRepository;
		$this->clientOrderService = $clientOrderService;
		$this->driverService = $driverService;
		$this->entityManager = $entityManager;
	}

	/**
	 * @param int $orderId
	 * @param string $phone
	 * @param string $surname
	 *
	 * @throws Exception
	 */
	public function saveDriverToOrder(int $orderId, string $phone, string $surname)
	{

		$driver = new Driver();
		$driver->setPhone($phone);
		$driver->setSurname($surname);
		$this->driverService->save($driver);

		$order = $this->clientOrderRepository->getById($orderId);

		if ($order === null) {
			throw new Exception;
		}
		$order->setDriver($driver);
		$this->entityManager->persist($order);
		$this->entityManager->flush($order);
		$this->clientOrderService->assignDriverToOrder($driver, $order);
	}
}
