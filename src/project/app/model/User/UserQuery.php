<?php

namespace App\Model\User;

use Kdyby\Doctrine\QueryBuilder;
use Kdyby\Doctrine\QueryObject;
use Kdyby\Persistence\Queryable;

class UserQuery extends QueryObject
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
		$qb = $repository->createQueryBuilder()->select('u')->from(User::class, 'u');

		foreach ($this->filter as $modifier) {
			$modifier($qb);
		}

		return $qb;
	}

	public function byEmail(string $email): self
	{
		$this->filter[] = function (QueryBuilder $qb) use ($email) {
			$qb->andWhere('u.email = :email')
			   ->setParameter('email', $email);
		};
		return $this;
	}

	public function byUsername(string $username): self
	{
		$this->filter[] = function (QueryBuilder $qb) use ($username) {
			$qb->andWhere('u.username = :username')
			   ->setParameter('username', $username);
		};
		return $this;
	}
}
