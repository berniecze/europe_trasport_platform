<?php
declare(strict_types=1);

namespace App\Model\Company;

use Kdyby\Doctrine\QueryBuilder;
use Kdyby\Doctrine\QueryObject;
use Kdyby\Persistence\Queryable;

class CompanyQuery extends QueryObject
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
		$qb = $repository->createQueryBuilder()->select('com')->from(Company::class, 'com');

		foreach ($this->filter as $modifier) {
			$modifier($qb);
		}

		return $qb;
	}
}
