<?php
declare(strict_types=1);

namespace Application\Transport\UseCase;

use Exception;
use Application\Transport\Request\UpdateTransportRequest;
use Infrastructure\Exception\TransportNotFoundException;
use Infrastructure\Service\TransportService;

class UpdateTransportUseCase
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
     * @param UpdateTransportRequest $request
     *
     * @throws TransportNotFoundException
     * @throws Exception
     */
    public function execute(UpdateTransportRequest $request): void
    {
        $this->transportService->update($request);
    }
}
