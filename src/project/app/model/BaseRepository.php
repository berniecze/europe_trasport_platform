<?php
declare(strict_types=1);

namespace App\Model;

use Doctrine\ORM\EntityManager;
use Kdyby\Doctrine\EntityRepository;
use Kdyby\Doctrine\QueryObject;

abstract class BaseRepository
{

	/**
	 * @var EntityRepository
	 */
	protected $repository;

	/**
	 * @var EntityManager
	 */
	protected $entityManager;

	abstract protected function getEntityName(): string;

	public function __construct(EntityManager $entityManager)
	{
		$this->repository = $entityManager->getRepository($this->getEntityName());
		$this->entityManager = $entityManager;
	}

	/**
	 * @param QueryObject $query
	 *
	 * @return array|\Kdyby\Doctrine\ResultSet
	 */
	public function fetchByQuery(QueryObject $query)
	{
		return $this->repository->fetch($query);
	}

	/**
	 * @param QueryObject $query
	 *
	 * @return object
	 */
	public function fetchOneByQuery(QueryObject $query)
	{
		return $this->repository->fetchOne($query);
	}

}
