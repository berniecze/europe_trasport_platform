<?php
declare(strict_types=1);

namespace App\AdminModule\Components\TransportList;

use App\Model\Transport\Transport;
use Application\Transport\UseCase\GetAllTransportUseCase;
use Exception;
use Tracy\Debugger;

class DataProvider
{

	/**
	 * @var GetAllTransportUseCase
	 */
	private $getAllTransportUseCase;

	public function __construct(GetAllTransportUseCase $getAllTransportUseCase)
	{
		$this->getAllTransportUseCase = $getAllTransportUseCase;
	}

	/**
	 * @return Transport[]
	 */
	public function getAllTransports(): array
	{
		try {
			return $this->getAllTransportUseCase->execute();
		} catch (Exception $exception) {
		    Debugger::log($exception);
			return [];
		}
	}
}
