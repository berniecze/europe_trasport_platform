<?php
declare(strict_types=1);

namespace Application\Transport\UseCase;

use Application\Transport\Request\GetTransportRequest;
use Domain\Entity\Transport\Transport;
use Exception;
use Infrastructure\Exception\TransportNotFoundException;
use Infrastructure\Service\TransportService;

class GetTransportUseCase
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
     * @param GetTransportRequest $request
     *
     * @return Transport
     * @throws TransportNotFoundException
     * @throws Exception
     */
    public function execute(GetTransportRequest $request): Transport
    {
        return $this->transportService->get($request);
    }
}
