<?php
declare(strict_types=1);

namespace App\Model\Route;

use Kdyby\Doctrine\EntityManager;
use Kdyby\Doctrine\EntityRepository;

class RouteService
{

	/**
	 * @var EntityManager $em
	 */
	private $em;

	/**
	 * @var EntityRepository $routeRepository
	 */
	private $routeRepository;

	public function __construct(EntityManager $em)
	{
		$this->em = $em;
		$this->routeRepository = $em->getRepository(Route::class);
	}

	public function getById(int $id): ?Route
	{
		return $this->routeRepository->find($id);
	}

	public function save(Route $route): void
	{
		$this->em->persist($route);
		$this->em->flush($route);
	}

	public function remove(Route $route): void
	{
		$this->em->remove($route);
		$this->em->flush($route);
	}
}
