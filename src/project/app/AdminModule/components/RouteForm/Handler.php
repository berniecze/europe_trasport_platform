<?php
declare(strict_types=1);

namespace App\AdminModule\Components\RouteForm;

use App\Model\Destination\DestinationRepository;
use App\Model\Destination\Exceptions\DestinationNotFoundException;
use App\Model\Route\Exceptions\RouteNotFoundException;
use App\Model\Route\Route;
use App\Model\Route\RouteService;

class Handler
{

	/**
	 * @var DestinationRepository $destinationRepository
	 */
	private $destinationRepository;

	/**
	 * @var RouteService $routeService
	 */
	private $routeService;

	public function __construct(
		DestinationRepository $destinationRepository,
		RouteService $routeService
	) {
		$this->destinationRepository = $destinationRepository;
		$this->routeService = $routeService;
	}

	/**
	 * @param array $values
	 *
	 * @throws DestinationNotFoundException
	 * @throws RouteNotFoundException
	 */
	public function handle(array $values): void
	{
		if (!$values['id']) {
			$route = new Route();
		} else {
			$route = $this->routeService->getById((int)$values['id']);
		}
		if ($route === null) {
			throw RouteNotFoundException::byPrimaryKey($values['id']);
		}

		$departure = $this->destinationRepository->getById($values['departure']);
		$arrival = $this->destinationRepository->getById($values['arrival']);

		$route->setDeparture($departure);
		$route->setArrival($arrival);
		$route->setPrice($values['price']);
		$route->setDistance($values['distance']);
		$route->setActive($values['active']);
		$route->setDuration($values['duration']);
		$this->routeService->save($route);
	}
}
