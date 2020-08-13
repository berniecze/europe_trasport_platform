<?php
declare(strict_types=1);

namespace Infrastructure\Service;

use Application\Blacklist\Request\GetBlacklistByDateRequest;
use Application\Transport\Request\CreateTransportRequest;
use Application\Transport\Request\GetAvailableTransportRequest;
use Application\Transport\Request\GetTransportRequest;
use Application\Transport\Request\RemoveTransportRequest;
use Application\Transport\Request\UpdateTransportRequest;
use Domain\Entity\Transport\Transport;
use Exception;
use Infrastructure\Exception\TransportNotFoundException;
use Infrastructure\Repository\Transport\TransportRepository;

class TransportService
{

    /**
     * @var TransportRepository
     */
    private $repository;
    /**
     * @var BlacklistService
     */
    private $blacklistService;

    public function __construct(TransportRepository $repository, BlacklistService $blacklistService)
    {
        $this->repository = $repository;
        $this->blacklistService = $blacklistService;
    }

    /**
     * @param CreateTransportRequest $request
     *
     * @throws Exception
     */
    public function create(CreateTransportRequest $request): void
    {
        $transport = new Transport(
            $request->getActive(),
            $request->getName(),
            $request->getCapacity(),
            $request->getLuggage(),
            $request->getFixedPrice(),
            $request->getMultiplierPrice()
        );

        if ($request->getDescription()) {
            $transport->setDescription($request->getDescription());
        }

        if ($request->getPhotoUrl()) {
            $transport->setPhotoUrl($request->getPhotoUrl());
        }

        $this->repository->save($transport);
    }

    /**
     * @param UpdateTransportRequest $request
     *
     * @throws TransportNotFoundException
     * @throws Exception
     */
    public function update(UpdateTransportRequest $request): void
    {
        $transport = $this->repository->getById($request->getId());

        $transport->setActive($request->getActive());
        $transport->setCapacity($request->getCapacity());
        $transport->setLuggage($request->getLuggage());
        $transport->setName($request->getName());
        $transport->setDescription($request->getDescription());
        $transport->setFixedPrice($request->getFixedPrice());
        $transport->setMultiplierPrice($request->getMultiplierPrice());

        if ($request->getPhotoUrl()) {
            $transport->setPhotoUrl($request->getPhotoUrl());
        }

        $this->repository->save($transport);
    }

    /**
     * @param RemoveTransportRequest $request
     *
     * @throws TransportNotFoundException
     * @throws Exception
     */
    public function remove(RemoveTransportRequest $request): void
    {
        $transport = $this->repository->getById($request->getId());
        $this->repository->remove($transport);
    }

    /**
     * @param GetTransportRequest $request
     *
     * @return Transport
     * @throws TransportNotFoundException
     * @throws Exception
     */
    public function get(GetTransportRequest $request): Transport
    {
        return $this->repository->getById($request->getId());
    }

    /**
     * @return Transport
     * @throws Exception
     */
    public function getTransportWithMaximalCapacity(): Transport
    {
        return $this->repository->getTransportWithMaximalCapacity();
    }

    /**
     * @return Transport[]
     * @throws Exception
     */
    public function getAll(): array
    {
         return $this->repository->getAll();
    }

	/**
	 * @param GetAvailableTransportRequest $request
	 *
	 * @throws Exception
	 * @return Transport[]
	 */
	public function getAvailableTransport(GetAvailableTransportRequest $request): array
	{
		$blacklistsForDay = $this->blacklistService->getByDate(new GetBlacklistByDateRequest($request->getPickupDate()));
		$blockedTransport = [];

		foreach ($blacklistsForDay as $blacklist) {
			$blockedTransport[] = $blacklist->getTransport();
		}

		return $this->repository->getAvailableTransport($request->getPassengers(), $blockedTransport);
    }
}
