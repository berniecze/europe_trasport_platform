<?php
declare(strict_types=1);

namespace Application\Blacklist\UseCase;

use Infrastructure\Service\BlacklistService;

class GetAllBlacklistUseCase
{

    /**
     * @var BlacklistService $blacklistService
     */
    private $blacklistService;

    public function __construct(BlacklistService $blacklistService)
    {
        $this->blacklistService = $blacklistService;
    }

    public function execute(): array
    {
        return $this->blacklistService->getAll();
    }
}
