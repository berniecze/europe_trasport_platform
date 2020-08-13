<?php
declare(strict_types=1);

namespace App\Model\Cart;

use App\Model\Cart\Exceptions\CartNotFoundException;
use App\Model\Transport\Exceptions\TransportNotFoundException;
use App\Model\Transport\TransportRepository;
use Application\Transport\Request\GetTransportRequest;
use Application\Transport\UseCase\GetTransportUseCase;
use Kdyby\Doctrine\EntityManager;
use Tracy\Debugger;

class CartService
{

	/**
	 * @var CartRepository
	 */
	private $cartRepository;

	/**
	 * @var TransportRepository
	 */
	private $transportRepository;

	/**
	 * @var GetTransportUseCase
	 */
	private $getTransportUseCase;

	/**
	 * @var EntityManager
	 */
	private $entityManager;

	public function __construct(
		CartRepository $cartRepository,
		TransportRepository $transportRepository,
		EntityManager $entityManager,
		GetTransportUseCase $getTransportUseCase
	) {
		$this->cartRepository = $cartRepository;
		$this->transportRepository = $transportRepository;
		$this->getTransportUseCase = $getTransportUseCase;
		$this->entityManager = $entityManager;
	}

	public function addTransportToCart(CartTransportRequest $cartTransportRequest)
	{
		try {
			$cart = $this->cartRepository->getByHash($cartTransportRequest->cartHash);

//			$transport = $this->getTransportUseCase->execute(new GetTransportRequest($cartTransportRequest->getTransportId()));
			// TODO switch to the commented line above after moving Cart to the DDD
			$transport = $this->transportRepository->getById($cartTransportRequest->getTransportId());
			$cart->setTransport($transport);

			$this->entityManager->persist($cart);
			$this->entityManager->flush();
			return true;
		} catch (CartNotFoundException $exception) {
			//do nothing
		} catch (TransportNotFoundException $exception) {
			//do nothing
		} catch (\Exception $exception) {
			Debugger::log($exception);
		}
		return false;
	}
}
