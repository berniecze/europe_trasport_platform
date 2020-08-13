<?php
declare(strict_types=1);

namespace App\FrontModule\Components\FindRouteFormControl;

use App\Model\Cart\Cart;
use App\Model\Destination\DestinationRepository;
use App\Model\Destination\Exceptions\DestinationNotFoundException;
use App\Model\Route\Exceptions\RouteNotFoundException;
use App\Model\Route\RouteRepository;
use Kdyby\Doctrine\EntityManager;
use Tracy\Debugger;

class Handler
{

	/**
	 * @var EntityManager $entityManager
	 */
	private $entityManager;

	/**
	 * @var DestinationRepository $destinationRepository
	 */
	private $destinationRepository;

	/**
	 * @var RouteRepository $routeRepository
	 */
	private $routeRepository;

	public function __construct(
		DestinationRepository $destinationRepository,
		EntityManager $entityManager,
		RouteRepository $routeRepository
	) {
		$this->destinationRepository = $destinationRepository;
		$this->routeRepository = $routeRepository;
		$this->entityManager = $entityManager;
	}

	/**
	 * @param int $departureId
	 * @param int $arrivalId
	 * @param \DateTime $date
	 * @param \DateTime $time
	 * @param int $passengers
	 *
	 * @return string|null
	 * @throws DestinationNotFoundException
	 * @throws RouteNotFoundException
	 */
	public function save(int $departureId, int $arrivalId, \DateTime $date, \DateTime $time, int $passengers): string
	{
		$departure = $this->destinationRepository->getById($departureId);
		$arrival = $this->destinationRepository->getById($arrivalId);

		$route = $this->routeRepository->getByDepartureAndArrival($departure, $arrival);
		$cartHash = $this->generateHash();

		$newCart = new Cart();
		$newCart->setRoute($route);
		$newCart->setDate($date);
		$newCart->setTime($time);
		$newCart->setHash(serialize($cartHash));
		$newCart->setStatus(Cart::STATUS_CREATED);
		$newCart->setPassengers($passengers);

		$this->entityManager->persist($newCart);
		$this->entityManager->flush($newCart);
		return $newCart->getHash();
	}

	private function generateHash()
	{
		$value = substr('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', random_int(0, 51), 1);
		return password_hash($value, PASSWORD_BCRYPT);
	}
}
