<?php
declare(strict_types=1);

namespace Infrastructure\Repository\Blacklist;

use Closure;
use DateTimeImmutable;
use Domain\Entity\Blacklist\Blacklist;
use Domain\Entity\Transport\Transport;
use Kdyby\Doctrine\QueryBuilder;
use Kdyby\Doctrine\QueryObject;
use Kdyby\Persistence\Queryable;

class BlacklistQuery extends QueryObject
{
    /**
     * @var array|Closure[]
     */
    private $filter = [];

    /**
     * @var array|Closure[]
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
                         ->select('bl')
                         ->from(Blacklist::class, 'bl');

        foreach ($this->filter as $modifier) {
            $modifier($qb);
        }

        return $qb;
    }

    public function filterByTransport(Transport $transport): self
    {
        $this->filter[] = function (QueryBuilder $builder) use ($transport) {
            $builder->andWhere('bl.transport = :transport')
                    ->setParameter('transport', $transport);
        };

        return $this;
    }

	public function filterByDate(DateTimeImmutable $datetime): self
	{
		$this->filter[] = function (QueryBuilder $qb) use ($datetime) {
			$qb->andWhere(' (bl.toDate  >=  :selectedDate OR bl.toDate IS NULL)
			AND bl.fromDate <= :selectedDate')
			   ->setParameter('selectedDate', $datetime);
		};
		return $this;
	}
}
