<?php
declare(strict_types=1);

namespace Application\Blacklist\UseCase;

use Application\Blacklist\Request\GetBlacklistByTransportRequest;
use Application\Blacklist\Request\GetBlacklistRequest;
use Domain\Entity\Blacklist\Blacklist;
use Exception;
use Infrastructure\Exception\BlacklistNotFoundException;
use Infrastructure\Service\BlacklistService;

class GetBlacklistByTransportUseCase
{

    /**
     * @var BlacklistService $blacklistService
     */
    private $blacklistService;

    public function __construct(BlacklistService $blacklistService)
    {
        $this->blacklistService = $blacklistService;
    }

    /**
     * @param GetBlacklistByTransportRequest $request
     *
     * @return Blacklist[]
     * @throws Exception
     */
    public function execute(GetBlacklistByTransportRequest $request): array
    {
        return $this->blacklistService->getByTransport($request);
    }
}
