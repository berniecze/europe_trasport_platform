<?php
declare(strict_types=1);

namespace Application\Blacklist\UseCase;

use Application\Blacklist\Request\UpdateBlacklistRequest;
use Exception;
use Infrastructure\Exception\BlacklistNotFoundException;
use Infrastructure\Service\BlacklistService;

class UpdateBlacklistUseCase
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
     * @param UpdateBlacklistRequest $request
     *
     * @throws BlacklistNotFoundException
     * @throws Exception
     */
    public function execute(UpdateBlacklistRequest $request): void
    {
        $this->blacklistService->update($request);
    }
}
