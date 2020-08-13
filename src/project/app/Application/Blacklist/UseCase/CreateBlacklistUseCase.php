<?php
declare(strict_types=1);

namespace Application\Blacklist\UseCase;

use Application\Blacklist\Request\CreateBlacklistRequest;
use Exception;
use Infrastructure\Service\BlacklistService;

class CreateBlacklistUseCase
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
     * @param CreateBlacklistRequest $request
     *
     * @throws Exception
     */
    public function execute(CreateBlacklistRequest $request): void
    {
        $this->blacklistService->create($request);
    }
}
