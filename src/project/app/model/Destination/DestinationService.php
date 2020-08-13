<?php
declare(strict_types=1);

namespace App\Model\Destination;

use App\Model\Route\Route;
use App\Model\Route\RouteQuery;
use Kdyby\Doctrine\EntityManager;
use Kdyby\Doctrine\EntityRepository;

class DestinationService
{

	/**
	 * @var EntityManager $em
	 */
	private $em;

	/**
	 * @var EntityRepository $destinationRepository
	 */
	private $destinationRepository;

	/**
	 * @var EntityRepository $routeRepository
	 */
	private $routeRepository;

	public function __construct(EntityManager $em)
	{
		$this->em = $em;
		$this->destinationRepository = $em->getRepository(Destination::class);
		$this->routeRepository = $em->getRepository(Route::class);
	}

	public function getById(int $id): ?Destination
	{
		return $this->destinationRepository->find($id);
	}

	public function save(Destination $route): void
	{
		$this->em->persist($route);
		$this->em->flush($route);
	}

	/**
	 * @param Destination $destination
	 *
	 * @throws \Exception
	 */
	public function removeWithRoutes(Destination $destination): void
	{
		$query = (new RouteQuery())
			->byDestinationId($destination->getId());
		$routes = $this->routeRepository->fetch($query);

		if ($routes->getTotalCount() !== 0) {
			foreach ($routes as $route) {
				$this->em->remove($route);
				$this->em->flush($route);
			}
		}
		$this->em->remove($destination);
		$this->em->flush($destination);
	}
}
