<?php

namespace App\Model\Transport;

use Kdyby\Doctrine\QueryBuilder;
use Kdyby\Doctrine\QueryObject;
use Kdyby\Persistence\Queryable;

class TransportQuery extends QueryObject
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
		$qb = $repository->createQueryBuilder()->select('t')->from(Transport::class, 't');

		foreach ($this->filter as $modifier) {
			$modifier($qb);
		}

		return $qb;
	}

	/**
	 * @param int[] $byExcluded
	 *
	 * @return $this
	 */
	public function byExcluded(array $byExcluded)
	{
		$this->filter[] = function (QueryBuilder $qb) use ($byExcluded) {
			$qb->andWhere('t.id NOT IN (:excludedIds)')
			   ->setParameter('excludedIds', $byExcluded);
		};
		return $this;
	}
}
