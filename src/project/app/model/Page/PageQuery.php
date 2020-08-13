<?php

namespace App\Model\Page;

use Kdyby\Doctrine\QueryBuilder;
use Kdyby\Doctrine\QueryObject;
use Kdyby\Persistence\Queryable;

class PageQuery extends QueryObject
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
			$qb->andWhere('pg.id = :pageId')->setParameter('pageId', $id);
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
						 ->select('pg')
						 ->from(Page::class, 'pg');

		foreach ($this->filter as $modifier) {
			$modifier($qb);
		}

		return $qb;
	}

	public function byUrl(string $name): self
	{
		$this->filter[] = function (QueryBuilder $qb) use ($name) {
			$qb->andWhere('pg.url = :url')
			   ->setParameter('url', $name);
		};
		return $this;
	}
}
