<?php
declare(strict_types=1);

namespace App\AdminModule\Components\BlacklistCollection;

use Application\Blacklist\Request\GetBlacklistByTransportRequest;
use Application\Blacklist\UseCase\GetAllBlacklistUseCase;
use Application\Blacklist\UseCase\GetBlacklistByTransportUseCase;
use Application\Transport\Request\GetTransportRequest;
use Application\Transport\UseCase\GetTransportUseCase;
use Domain\Entity\Blacklist\Blacklist;
use Exception;
use Infrastructure\Exception\TransportNotFoundException;

class DataProvider
{

	/**
	 * @var GetAllBlacklistUseCase
	 */
	private $getAllBlacklistUseCase;

	/**
	 * @var GetBlacklistByTransportUseCase
	 */
	private $getBlacklistByTransportUseCase;

	/**
	 * @var GetTransportUseCase
	 */
	private $getTransportUseCase;

	public function __construct(
	    GetAllBlacklistUseCase $getAllBlacklistUseCase,
	    GetBlacklistByTransportUseCase $getBlacklistByTransportUseCase,
        GetTransportUseCase $getTransportUseCase)
	{
		$this->getAllBlacklistUseCase = $getAllBlacklistUseCase;
		$this->getBlacklistByTransportUseCase = $getBlacklistByTransportUseCase;
		$this->getTransportUseCase = $getTransportUseCase;
	}

	/**
	 * @return Blacklist[]
     */
	public function getAllBlacklists()
	{
		try {
			return $this->getAllBlacklistUseCase->execute();
		} catch (Exception $exception) {
			return [];
		}
	}

    /**
     * @param int $transportId
     *
     * @return Blacklist[]
     * @throws TransportNotFoundException
     * @throws Exception
     */
	public function getForTransport(int $transportId): array
	{
        $transport = $this->getTransportUseCase->execute(new GetTransportRequest($transportId));

        return $this->getBlacklistByTransportUseCase->execute(new GetBlacklistByTransportRequest($transport));
	}
}
