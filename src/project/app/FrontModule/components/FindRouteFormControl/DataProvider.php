<?php
declare(strict_types=1);

namespace App\FrontModule\Components\FindRouteFormControl;

use App\Model\Cart\Cart;
use App\Model\Cart\CartRepository;
use App\Model\Cart\Exceptions\CartNotFoundException;
use App\Model\Cart\Exceptions\InvalidCartException;
use App\Model\DateTimeService;
use App\Model\Destination\Destination;
use App\Model\Destination\DestinationRepository;
use App\Model\Destination\Exceptions\DestinationNotFoundException;
use Application\Transport\UseCase\GetTransportMaximalCapacityUseCase;
use Domain\Entity\Transport\ValueObject\Capacity;
use Exception;

class DataProvider
{

	/**
	 * @var DestinationRepository $destinationRepository
	 */
	private $destinationRepository;

	/**
	 * @var CartRepository $cartRepository
	 */
	private $cartRepository;

	/**
	 * @var DateTimeService $dateTimeService
	 */
	private $dateTimeService;

	/**
	 * @var GetTransportMaximalCapacityUseCase $maximalCapacityUseCase
	 */
	private $maximalCapacityUseCase;

	public function __construct(
		DestinationRepository $destinationRepository,
		CartRepository $cartRepository,
		DateTimeService $dateTimeService,
        GetTransportMaximalCapacityUseCase $maximalCapacityUseCase
	) {
		$this->destinationRepository = $destinationRepository;
		$this->cartRepository = $cartRepository;
		$this->dateTimeService = $dateTimeService;
		$this->maximalCapacityUseCase = $maximalCapacityUseCase;
	}

	public function getDestinationsForSelect(): array
	{
		$data = [];
		try {
			foreach ($this->destinationRepository->getOrderedByCountryForSelect() as $destination) {
				/** @var Destination $destination */
				$data[$destination->getId()] = $destination->getName();
			}
		} catch (DestinationNotFoundException $exception) {
			//do nothing
		}
		return $data;
	}

	public function getDestinationsForSelectTemplate(): array
	{
		$data = [];
		try {
			foreach ($this->destinationRepository->getOrderedByCountryForSelect() as $destination) {
				/** @var Destination $destination */
				$data[$destination->getId()] = ['id'   => $destination->getId(),
												'name' => $destination->getName(),
												'type' => $destination->getType(),
				];
			}
		} catch (DestinationNotFoundException $exception) {
			//do nothing
		}
		return $data;
	}

	/**
	 * @param string $cartHash
	 *
	 * @return Cart|null
	 */
	public function getValidCart(string $cartHash): ?Cart
	{
		try {
			$cart = $this->cartRepository->getByHash($cartHash);
			$today = DateTimeService::convertImmutableToMutable($this->dateTimeService->getActualDateTime());

			if ($cart->getDate() <= $today->modify('2 days')) {
				throw InvalidCartException::byHash($cartHash);
			}
			return $cart;
		} catch (CartNotFoundException $exception) {
			//do nothing
		} catch (Exception $exception) {
			//do nothing
		}
		return null;
	}

	public function getMaximalCapacity(): Capacity
	{
	    try {
	        return $this->maximalCapacityUseCase->execute();
        } catch (Exception $exception) {
	        return new Capacity(0);
        }
	}
}
