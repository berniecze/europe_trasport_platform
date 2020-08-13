<?php
declare(strict_types=1);

namespace App\Model\Route;

use App\Model\BaseRepository;
use App\Model\Destination\Destination;
use App\Model\Route\Exceptions\RouteNotFoundException;
use Kdyby\Doctrine\ResultSet;

class RouteRepository extends BaseRepository
{

	protected function getEntityName(): string
	{
		return Route::class;
	}

	public function getAll(): ResultSet
	{
		$query = new RouteQuery();

		$data = $this->repository->fetch($query);

		if ($data->getTotalCount() === 0) {
			throw new RouteNotFoundException;
		}

		return $data;
	}

	/**
	 * @param Destination $departure
	 * @param Destination $arrival
	 *
	 * @return Route
	 * @throws RouteNotFoundException
	 */
	public function getByDepartureAndArrival(Destination $departure, Destination $arrival): Route
	{
		$query = (new RouteQuery())
			->byDepartureId($departure->getId())
			->byArrivalId($arrival->getId());

		/** @var Route $route */
		$route = $this->repository->fetchOne($query);
		if ($route === null) {
			throw new RouteNotFoundException();
		}
		return $route;
	}

	/**
	 * @param int $id
	 *
	 * @return Route
	 * @throws RouteNotFoundException
	 */
	public function getById(int $id): Route
	{
		/** @var Route $entity */
		$entity = $this->repository->find($id);

		if (!$entity) {
			throw RouteNotFoundException::byPrimaryKey($id);
		}
		return $entity;
	}
}
