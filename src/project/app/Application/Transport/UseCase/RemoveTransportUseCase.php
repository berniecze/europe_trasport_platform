<?php
declare(strict_types=1);

namespace Application\Transport\UseCase;

use App\Model\PhotoService;
use Exception;
use Application\Transport\Request\RemoveTransportRequest;
use Infrastructure\Service\TransportService;

class RemoveTransportUseCase
{

    /**
     * @var TransportService
     */
    private $transportService;

    /**
     * @var PhotoService
     */
    private $photoService;

    public function __construct(
        TransportService $transportService,
        PhotoService $photoService
    ) {
        $this->transportService = $transportService;
        $this->photoService = $photoService;
    }

    /**
     * @param RemoveTransportRequest $request
     *
     * @throws Exception
     */
    public function execute(RemoveTransportRequest $request): void
    {
        $this->photoService->removeTransportPhoto($request->getPhotoUrl()->getValue());
        $this->transportService->remove($request);
    }
}
