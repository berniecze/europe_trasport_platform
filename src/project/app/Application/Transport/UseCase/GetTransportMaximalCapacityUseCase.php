<?php
declare(strict_types=1);

namespace Application\Transport\UseCase;

use Exception;
use Domain\Entity\Transport\ValueObject\Capacity;
use Infrastructure\Service\TransportService;

class GetTransportMaximalCapacityUseCase
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
     * @return Capacity
     * @throws Exception
     */
    public function execute(): Capacity
    {
        $transport = $this->transportService->getTransportWithMaximalCapacity();

        return $transport->getCapacity();
    }
}
