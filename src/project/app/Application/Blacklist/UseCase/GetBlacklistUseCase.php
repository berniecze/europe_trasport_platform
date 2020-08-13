<?php
declare(strict_types=1);

namespace Application\Blacklist\UseCase;

use Application\Blacklist\Request\GetBlacklistRequest;
use Domain\Entity\Blacklist\Blacklist;
use Exception;
use Infrastructure\Exception\BlacklistNotFoundException;
use Infrastructure\Service\BlacklistService;

class GetBlacklistUseCase
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
     * @param GetBlacklistRequest $request
     *
     * @return Blacklist
     * @throws BlacklistNotFoundException
     * @throws Exception
     */
    public function execute(GetBlacklistRequest $request): Blacklist
    {
        return $this->blacklistService->get($request);
    }
}
