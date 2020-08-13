<?php
declare(strict_types=1);

namespace App\AdminModule\Components\ClientOrderForm;

use App\Model\ClientOrder\ClientOrderRepository;
use App\Model\ClientOrder\Exceptions\ClientOrderNotFoundException;
use Exception;
use Kdyby\Doctrine\EntityManager;

class Handler
{

	/**
	 * @var ClientOrderRepository
	 */
	private $clientOrderRepository;

	/**
	 * @var EntityManager
	 */
	private $entityManager;

	public function __construct(ClientOrderRepository $clientOrderRepository, EntityManager $entityManager)
	{
		$this->clientOrderRepository = $clientOrderRepository;
		$this->entityManager = $entityManager;
	}

	/**
	 * @param array $values
	 *
	 * @throws ClientOrderNotFoundException
	 * @throws Exception
	 */
	public function handle(array $values): void
	{
		if (!$values['id']) {
			throw new ClientOrderNotFoundException();
		}
		$newStatus = (int)$values['status'];
		$newFinalPrice = (float)$values['final_price'];

		$order = $this->clientOrderRepository->getById((int)$values['id']);

		$order->setStatus($newStatus);
		$order->setFinalPrice($newFinalPrice);

		$this->entityManager->persist($order);
		$this->entityManager->flush($order);
	}
}
