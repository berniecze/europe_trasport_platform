<?php
declare(strict_types=1);

namespace App\AdminModule\Components\RouteList;

use App\Model\Route\Exceptions\RouteNotFoundException;
use App\Model\Route\Route;
use App\Model\Route\RouteRepository;
use Kdyby\Doctrine\ResultSet;

class DataProvider
{

	/**
	 * @var RouteRepository
	 */
	private $repository;

	public function __construct(RouteRepository $repository)
	{
		$this->repository = $repository;
	}

	/**
	 * @return Route[]|ResultSet
	 * @throws RouteNotFoundException
	 */
	public function getAllRoutes()
	{
		try {
			return $this->repository->getAll();
		} catch (RouteNotFoundException $exception) {
			throw $exception;
		}
	}
}
