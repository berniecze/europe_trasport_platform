<?php
declare(strict_types=1);

namespace Application\Transport\UseCase;

use Application\Transport\Request\GetAvailableTransportRequest;
use Domain\Entity\Transport\Transport;
use Infrastructure\Service\TransportService;

class GetAvailableTransportUseCase
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
	 * @param GetAvailableTransportRequest $request
	 *
	 * @return Transport[]
	 */
	public function execute(GetAvailableTransportRequest $request): array
	{
		$this->transportService->getAvailableTransport($request);
	}
}
