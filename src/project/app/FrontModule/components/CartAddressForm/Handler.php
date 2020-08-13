<?php
declare(strict_types=1);

namespace App\FrontModule\Components\CartAddressForm;

use App\Model\Cart\Cart;
use App\Model\Client\Client;
use App\Model\Client\ClientRequest;
use App\Model\ClientOrder\ClientOrder;
use App\Model\ClientOrder\ClientOrderRepository;
use App\Model\ClientOrder\Exceptions\ClientOrderNotFoundException;
use App\Model\Transport\TransportPriceCalculator;
use DateTime;
use Exception;
use Kdyby\Doctrine\EntityManager;
use Tracy\Debugger;

class Handler
{

	/**
	 * @var EntityManager
	 */
	private $entityManager;

	/**
	 * @var ClientOrderRepository
	 */
	private $orderRepository;

	/**
	 * @var TransportPriceCalculator
	 */
	private $transportPriceCalculator;

	public function __construct(
		EntityManager $entityManager,
		ClientOrderRepository $orderRepository,
		TransportPriceCalculator $transportPriceCalculator
	) {
		$this->entityManager = $entityManager;
		$this->orderRepository = $orderRepository;
		$this->transportPriceCalculator = $transportPriceCalculator;
	}

	public function save(ClientRequest $request, Cart $cart): ?int
	{
		try {
			$client = new Client();
			$client->setName($request->getName());
			$client->setLastname($request->getLastname());
			$client->setEmail($request->getEmail());
			$client->setPhone($request->getPhone());
			$client->setTicketNumber($request->getTicketNumber());
			$client->setExtraCargo($request->getExtraCargo());
			$client->setFromAddress($request->getFromAddress());
			$client->setToAddress($request->getToAddress());
			$client->setCreated($request->getCreated());
			$client->setReturnDepartureDatetime($request->getReturnDepartureDatetime());
			$client->setTransportTicketReturn($request->getReturnTickerNumber());
			$this->entityManager->persist($client);

			$cart->setClient($client);
			$cart->setNotes($request->getNotes());
			$cart->setStatus(Cart::STATUS_CREATED_ORDER);
			$this->entityManager->persist($cart);

			$order = new ClientOrder();
			$order->setStatus(ClientOrder::NOT_ACCEPTED_ORDERS);
			$order->setPayId('loremIpsum');
			$order->setNumber($this->getNumberForNewOrder());
			$order->setCart($cart);
			$finalPrice = $this->transportPriceCalculator->getTotalPrice($cart);
			$order->setFinalPrice($finalPrice);
			$order->setCreated(new DateTime());
			$this->entityManager->persist($order);
			$this->entityManager->flush();

			return $order->getId();
		} catch (Exception $exception) {
			Debugger::log($exception);
			return null;
		}
	}

	private function getNumberForNewOrder(): string
	{
		try {
			$newestOrder = $this->orderRepository->getByHighestId();
			$dateTime = new DateTime();
			return sprintf(
				'%s#%s%s%s',
				'W2T',
				$dateTime->format('Y'),
				$dateTime->format('m'),
				$newestOrder->getId() + 1
			);
		} catch (ClientOrderNotFoundException $exception) {
			return '1';
		}

	}
}
