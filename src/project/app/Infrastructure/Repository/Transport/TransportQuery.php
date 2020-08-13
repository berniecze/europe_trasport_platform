<?php
declare(strict_types=1);

namespace Infrastructure\Repository\Transport;

use Doctrine\Common\Collections\Criteria;
use Domain\Entity\Transport\Transport;
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
             ->select('t')
             ->from(Transport::class, 't');

        foreach ($this->filter as $modifier) {
            $modifier($qb);
        }

        return $qb;
    }

    public function orderByCapacityDesc(): self
    {
        $this->filter[] = function (QueryBuilder $builder) {
            $builder->orderBy('t.capacity.value', Criteria::DESC);
        };

        return $this;
    }

    public function offset(int $offset): self
    {
        $this->filter[] = function (QueryBuilder $builder) use ($offset) {
            $builder->setFirstResult($offset);
        };

        return $this;
    }

    public function limitResults(int $limit): self
    {
        $this->filter[] = function (QueryBuilder $builder) use ($limit) {
            $builder->setMaxResults($limit);
        };

        return $this;
    }

	public function limitByPassengers(int $passengers): self
	{
		$this->filter[] = function (QueryBuilder $qb) use ($passengers) {
			$qb->andWhere(' (t.capacity  >=  :passengers')
			   ->setParameter('passengers', $passengers);
		};

		return $this;
	}

	/**
	 * @param int[] $blacklistedTransportIds
	 *
	 * @return $this
	 */
	public function excludeTransport(array $blacklistedTransportIds): self
	{
		$this->filter[] = function (QueryBuilder $qb) use ($blacklistedTransportIds) {
			$qb->andWhere('t.id NOT IN (:excludedIds)')
			   ->setParameter('excludedIds', $blacklistedTransportIds);
		};

		return $this;
	}
}
