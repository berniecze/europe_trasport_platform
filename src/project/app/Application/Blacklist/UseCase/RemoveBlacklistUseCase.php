<?php
declare(strict_types=1);

namespace Application\Blacklist\UseCase;

use Application\Blacklist\Request\RemoveBlacklistRequest;
use Infrastructure\Service\BlacklistService;

class RemoveBlacklistUseCase
{

    /**
     * @var BlacklistService $blacklistService
     */
    private $blacklistService;

    public function __construct(BlacklistService $blacklistService)
    {
        $this->blacklistService = $blacklistService;
    }

    public function execute(RemoveBlacklistRequest $request): void
    {
        $this->blacklistService->remove($request);
    }
}
