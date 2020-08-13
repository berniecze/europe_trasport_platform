<?php
declare(strict_types=1);

namespace Infrastructure\Service;

use Application\Blacklist\Request\GetBlacklistByDateRequest;
use Application\Blacklist\Request\GetBlacklistByTransportRequest;
use Application\Blacklist\Request\GetBlacklistRequest;
use Application\Blacklist\Request\RemoveBlacklistRequest;
use Application\Blacklist\Request\UpdateBlacklistRequest;
use Exception;
use Application\Blacklist\Request\CreateBlacklistRequest;
use Domain\Entity\Blacklist\Blacklist;
use Infrastructure\Exception\BlacklistNotFoundException;
use Infrastructure\Repository\Blacklist\BlacklistRepository;

class BlacklistService
{

    /**
     * @var BlacklistRepository $blacklistRepository
     */
    private $blacklistRepository;

    public function __construct(BlacklistRepository $blacklistRepository)
    {
        $this->blacklistRepository = $blacklistRepository;
    }

    /**
     * @param CreateBlacklistRequest $createBlacklistRequest
     *
     * @throws Exception
     */
    public function create(CreateBlacklistRequest $createBlacklistRequest): void
    {
        $blacklist = new Blacklist(
            $createBlacklistRequest->getTransport(),
            $createBlacklistRequest->getFromDate(),
            $createBlacklistRequest->getToDate()
        );

        $this->blacklistRepository->save($blacklist);
    }

    /**
     * @param UpdateBlacklistRequest $request
     *
     * @throws BlacklistNotFoundException
     * @throws Exception
     */
    public function update(UpdateBlacklistRequest $request): void
    {
        $blacklist = $this->blacklistRepository->getById($request->getId());

        $blacklist->setFromDate($request->getFromDate());
        $blacklist->setToDate($request->getToDate());
        $blacklist->setTransport($request->getTransport());

        $this->blacklistRepository->save($blacklist);
    }

    /**
     * @param GetBlacklistRequest $request
     *
     * @return Blacklist
     * @throws BlacklistNotFoundException
     * @throws Exception
     */
    public function get(GetBlacklistRequest $request): Blacklist
    {
        return $this->blacklistRepository->getById($request->getId());
    }

    /**
     * @param RemoveBlacklistRequest $request
     *
     * @throws BlacklistNotFoundException
     * @throws Exception
     */
    public function remove(RemoveBlacklistRequest $request): void
    {
        $blacklist = $this->blacklistRepository->getById($request->getId());
        $this->blacklistRepository->remove($blacklist);
    }

    /**
     * @return Blacklist[]
     * @throws Exception
     */
    public function getAll(): array
    {
        return $this->blacklistRepository->getAll();
    }

    /**
     * @param GetBlacklistByTransportRequest $request
     * @return Blacklist[]
     * @throws Exception
     */
    public function getByTransport(GetBlacklistByTransportRequest $request): array
    {
        return $this->blacklistRepository->getByTransport($request->getTransport());
    }

	/**
	 * @param GetBlacklistByDateRequest $request
	 *
	 * @return Blacklist[]
	 * @throws Exception
	 */
	public function getByDate(GetBlacklistByDateRequest $request): array
	{
		return $this->blacklistRepository->getByDate($request->getDate());
    }
}
