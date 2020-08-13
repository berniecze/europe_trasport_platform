<?php
declare(strict_types=1);

namespace App\AdminModule\Components\BlacklistForm;

use Application\Transport\UseCase\GetAllTransportUseCase;
use Domain\Entity\Transport\Transport;
use Exception;

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

	public function getTransportsForSelect(): array
	{
		try {
			$transports = $this->getAllTransportUseCase->execute();
			$data = [];

			foreach ($transports as $transport) {
				/** @var Transport $transport */
				$data[$transport->getId()] = $transport->getName()->getValue();
			}
			return $data;
		} catch (Exception $exception) {
			return [];
		}
	}
}
