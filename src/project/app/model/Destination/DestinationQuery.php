<?php

namespace App\Model\Destination;

use Doctrine\Common\Collections\Criteria;
use Kdyby\Doctrine\QueryBuilder;
use Kdyby\Doctrine\QueryObject;
use Kdyby\Persistence\Queryable;

class DestinationQuery extends QueryObject
{

	/**
	 * @var array|\Closure[]
	 */
	private $filter = [];

	/**
	 * @var array|\Closure[]
	 */
	private $select = [];

	public function byId(int $id): self
	{
		$this->filter[] = function (QueryBuilder $qb) use ($id) {
			$qb->andWhere('d.id = :destinationId')->setParameter('destinationId', $id);
		};

		return $this;
	}

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
		$qb = $repository->createQueryBuilder()
						 ->select('d')
						 ->from(Destination::class, 'd')
						 ->orderBy('d.name', Criteria::ASC);

		foreach ($this->filter as $modifier) {
			$modifier($qb);
		}

		return $qb;
	}

	public function byName(string $name): self
	{
		$this->filter[] = function (QueryBuilder $qb) use ($name) {
			$qb->andWhere('d.name = :name')
			   ->setParameter('name', $name);
		};
		return $this;
	}

	public function byIds(array $ids): self
	{
		$this->filter[] = function (QueryBuilder $qb) use ($ids) {
			$qb->andWhere('d.id IN (:ids)')
			   ->setParameter('ids', $ids);
		};
		return $this;
	}

	public function orderedByCountry(): self
	{
		$this->filter[] = function (QueryBuilder $qb) {
			$qb->leftJoin('d.country', 'c')
			   ->orderBy('c.name, d.name', Criteria::ASC);
		};
		return $this;
	}
}
