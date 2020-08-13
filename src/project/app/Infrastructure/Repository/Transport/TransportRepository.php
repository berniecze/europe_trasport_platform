<?php
declare(strict_types=1);

namespace Infrastructure\Repository\Transport;

use DateTimeImmutable;
use Exception;
use Doctrine\ORM\EntityManagerInterface;
use Domain\Entity\Transport\Transport;
use Infrastructure\Exception\TransportNotFoundException;
use Kdyby\Doctrine\EntityRepository;

class TransportRepository
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
        $this->repository = $entityManager->getRepository(Transport::class);
        $this->entityManager = $entityManager;
    }

    /**
     * @param Transport $transport
     * @throws Exception
     */
    public function save(Transport $transport): void
    {
        $this->entityManager->persist($transport);
        $this->entityManager->flush();
    }

    /**
     * @param int $id
     *
     * @return Transport
     * @throws TransportNotFoundException
     */
    public function getById(int $id): Transport
    {
        /** @var Transport|null $fetchedTransport */
        $fetchedTransport = $this->repository->find($id);

        if ($fetchedTransport === null) {
            throw new TransportNotFoundException($id);
        }

        return  $fetchedTransport;
    }

    /**
     * @param Transport $transport
     *
     * @throws Exception
     */
    public function remove(Transport $transport): void
    {
        $this->entityManager->remove($transport);
        $this->entityManager->flush();
    }

    /**
     * @return Transport
     *
     * @throws Exception
     */
    public function getTransportWithMaximalCapacity(): Transport
    {
        $query = (new TransportQuery())
            ->orderByCapacityDesc();

        /** @var Transport $transport */
        $transport = $this->repository->fetchOne($query);

        return $transport;
    }

    /**
     * @throws Exception
     * @return Transport[]
     */
    public function getAll(): array
    {
        $query = (new TransportQuery());

        return $this->repository->findAll($query);
    }

	/**
	 * @param int $passengers
	 * @param Transport[] $blacklistedTransport
	 *
	 * @return Transport[]
	 */
	public function getAvailableTransport(int $passengers, array $blacklistedTransport): array
	{
		$blacklistedTransportId = [];
		foreach ($blacklistedTransport as $transport) {
			$blacklistedTransportId[] = $transport->getId();
		}

		$query = (new TransportQuery());
		$query->limitByPassengers($passengers);
		$query->excludeTransport($blacklistedTransportId);
		// TODO maybe add a check for orders for that day
		return $this->repository->fetch($query);
    }
}
