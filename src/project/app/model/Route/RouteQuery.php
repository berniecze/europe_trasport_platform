<?php

namespace App\Model\Route;

use Kdyby\Doctrine\QueryBuilder;
use Kdyby\Doctrine\QueryObject;
use Kdyby\Persistence\Queryable;

class RouteQuery extends QueryObject
{

	/**
	 * @var array|\Closure[]
	 */
	private $filter = [];

	/**
	 * @var array|\Closure[]
	 */
	private $select = [];

	/**
	 * @inheritdoc
	 */
	protected function doCreateQuery(Queryable $repository)
	{
		$qb = $this->createBasicDql($repository);

		foreach ($this->select as $modifier) {
			$modifier($qb);
		}

		return $qb;
	}

	private function createBasicDql(Queryable $repository): QueryBuilder
	{
		$qb = $repository->createQueryBuilder()->select('r')->from(Route::class, 'r');

		foreach ($this->filter as $modifier) {
			$modifier($qb);
		}

		return $qb;
	}

	public function byDepartureId(int $departureId): self
	{
		$this->filter[] = function (QueryBuilder $qb) use ($departureId) {
			$qb->andWhere('r.departure = :departureId')
			   ->setParameter('departureId', $departureId);
		};
		return $this;
	}

	public function byArrivalId(int $arrivalId): self
	{
		$this->filter[] = function (QueryBuilder $qb) use ($arrivalId) {
			$qb->andWhere('r.arrival = :arrivalId')
			   ->setParameter('arrivalId', $arrivalId);
		};
		return $this;
	}

	public function byDestinationId(int $destinationId)
	{
		$this->filter[] = function (QueryBuilder $qb) use ($destinationId) {
			$qb->andWhere('r.departure = :destinationId OR r.arrival = :destinationId')
			   ->setParameter('destinationId', $destinationId);
		};
		return $this;
	}
}
