<?php
declare(strict_types=1);

namespace App\AdminModule\Components\RouteForm;

use App\Model\Destination\Destination;
use App\Model\Destination\DestinationRepository;
use App\Model\Destination\Exceptions\DestinationNotFoundException;

class DataProvider
{
	/**
	 * @var DestinationRepository
	 */
	private $destinationRepository;

	public function __construct(DestinationRepository $destinationRepository)
	{
		$this->destinationRepository = $destinationRepository;
	}

	public function getDestinationsForSelect(): array
	{
		try {
			$destinations = $this->destinationRepository->getAll();
			$data = [];
			foreach ($destinations as $destination) {
				/**  @var Destination $destination */
				$data[$destination->getId()] = $destination->getName();
			}
			return $data;
		} catch (DestinationNotFoundException $exception) {
			return [];
		}
	}
}
