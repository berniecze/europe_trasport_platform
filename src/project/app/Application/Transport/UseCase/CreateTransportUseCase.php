<?php
declare(strict_types=1);

namespace Application\Transport\UseCase;

use Exception;
use Application\Transport\Request\CreateTransportRequest;
use Infrastructure\Service\TransportService;

class CreateTransportUseCase
{

    /**
     * @var TransportService
     */
    private $transportService;

    public function __construct(TransportService $transportService)
    {
        $this->transportService = $transportService;
    }

    /**
     * @param CreateTransportRequest $request
     *
     * @throws Exception
     */
    public function execute(CreateTransportRequest $request): void
    {
        $this->transportService->create($request);
    }
}
