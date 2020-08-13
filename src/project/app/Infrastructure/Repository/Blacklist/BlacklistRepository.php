<?php
declare(strict_types=1);

namespace Infrastructure\Repository\Blacklist;

use DateTimeImmutable;
use Doctrine\ORM\Query;
use Domain\Entity\Transport\Transport;
use Exception;
use Doctrine\ORM\EntityManagerInterface;
use Domain\Entity\Blacklist\Blacklist;
use Infrastructure\Exception\BlacklistNotFoundException;
use Kdyby\Doctrine\EntityRepository;
use Tracy\Debugger;

class BlacklistRepository
{

    /**
     * @var EntityRepository
     */
    private $repository;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->repository = $entityManager->getRepository(Blacklist::class);
        $this->entityManager = $entityManager;
    }

    /**
     * @param int $id
     *
     * @return Blacklist
     * @throws BlacklistNotFoundException
     */
    public function getById(int $id): Blacklist
    {
        /** @var Blacklist|null $blacklist */
        $blacklist = $this->repository->find($id);

        if ($blacklist === null) {
            throw new BlacklistNotFoundException($id);
        }

        return  $blacklist;
    }

    /**
     * @param Blacklist $blacklist
     *
     * @throws Exception
     */
    public function save(Blacklist $blacklist): void
    {
        $this->entityManager->persist($blacklist);
        $this->entityManager->flush();
    }

    /**
     * @param Blacklist $blacklist
     *
     * @throws Exception
     */
    public function remove(Blacklist $blacklist): void
    {
        $this->entityManager->remove($blacklist);
        $this->entityManager->flush();
    }

    /**
     * @throws Exception
     * @return Blacklist[]
     */
    public function getAll(): array
    {
        $query = (new BlacklistQuery());

        return $this->repository->findAll($query);
    }

    /**
     * @param Transport $transport
     *
     * @throws Exception
     * @return Blacklist[]
     */
    public function getByTransport(Transport $transport): array
    {
        $query = (new BlacklistQuery());

        $query->filterByTransport($transport);
        return $this->repository->fetch($query);
	}

	/**
	 * @param DateTimeImmutable $date
	 *
	 * @throws Exception
	 * @return array
	 */
	public function getByDate(DateTimeImmutable $date): array
	{
		$query = (new BlacklistQuery());

		$query->filterByDate($date);
		return $this->repository->fetch($query);
	}
}
