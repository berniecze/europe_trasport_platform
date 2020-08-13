<?php

namespace App\Model\Cart;

use DateTimeInterface;
use Kdyby\Doctrine\QueryBuilder;
use Kdyby\Doctrine\QueryObject;
use Kdyby\Persistence\Queryable;

class CartQuery extends QueryObject
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
		$qb = $repository->createQueryBuilder()->select('c')->from(Cart::class, 'c');

		foreach ($this->filter as $modifier) {
			$modifier($qb);
		}

		return $qb;
	}

	/**
	 * @param string $cartHash
	 *
	 * @return $this
	 */
	public function byHash(string $cartHash)
	{
		$this->filter[] = function (QueryBuilder $qb) use ($cartHash) {
			$qb->andWhere('c.hash = :cartHash')
			   ->setParameter('cartHash', $cartHash);
		};
		return $this;
	}

	public function byCartDate(DateTimeInterface $date): self
	{
		$this->filter[] = function (QueryBuilder $qb) use ($date) {
			$qb->andWhere('c.date BETWEEN :dateMin AND :dateMax')
			   ->setParameters(
				   [
					   'dateMin' => $date->format('Y-m-d 00:00:00'),
					   'dateMax' => $date->format('Y-m-d 23:59:59'),
				   ]
			   );
		};
		return $this;
	}
}
