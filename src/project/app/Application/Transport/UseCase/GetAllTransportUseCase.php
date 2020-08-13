<?php
declare(strict_types=1);

namespace Application\Transport\UseCase;

use Application\Transport\Request\GetAllTransportRequest;
use Domain\Entity\Transport\Transport;
use Exception;
use Infrastructure\Service\TransportService;

class GetAllTransportUseCase
{

    /**
     * @var TransportService $transportService
     */
    private $transportService;

    public function __construct(TransportService $transportService)
    {
        $this->transportService = $transportService;
    }

    /**
     * @throws Exception
     * @return Transport[]
     */
    public function execute(): array
    {
        return $this->transportService->getAll();
    }
}
