<?php
declare(strict_types=1);

namespace App\Model\ClientOrder;

use App\Model\Cart\Cart;
use DateTimeInterface;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Query\Expr\Join;
use Kdyby\Doctrine\QueryBuilder;
use Kdyby\Doctrine\QueryObject;
use Kdyby\Persistence\Queryable;

class ClientOrderQuery extends QueryObject
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
		$qb = $repository->createQueryBuilder()
						 ->select('o')
						 ->from(ClientOrder::class, 'o');

		foreach ($this->filter as $modifier) {
			$modifier($qb);
		}

		return $qb;
	}

	public function orderById(): self
	{
		$this->filter[] = function (QueryBuilder $qb) {
			$qb->orderBy('o.id', Criteria::DESC);
		};
		return $this;
	}

	public function onlyAccepted(): self
	{
		$this->filter[] = function (QueryBuilder $qb) {
			$qb->andWhere('o.status = :status')
			   ->setParameter('status', ClientOrder::ACCEPTED_ORDERS);
		};
		return $this;
	}

	public function onlyNotAccepted(): self
	{
		$this->filter[] = function (QueryBuilder $qb) {
			$qb->andWhere('o.status = :status')
			   ->setParameter('status', ClientOrder::NOT_ACCEPTED_ORDERS);
		};
		return $this;
	}

	public function onlyFinished(): self
	{
		$this->filter[] = function (QueryBuilder $qb) {
			$qb->andWhere('o.status = :status')
			   ->setParameter('status', ClientOrder::FINISHED_ORDERS);
		};
		return $this;
	}

	public function byTransportDate(DateTimeInterface $date): self
	{
		$this->filter[] = function (QueryBuilder $qb) use ($date) {
			$qb->leftJoin(Cart::class, 'c', Join::WITH, 'o.cart = c.id')
				->andWhere('c.date >= :date')
				->setParameter('date', $date)
				->orderBy('c.date', Criteria::ASC);
		};
		return $this;
	}
}
