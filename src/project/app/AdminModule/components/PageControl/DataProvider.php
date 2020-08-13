<?php
declare(strict_types=1);

namespace App\AdminModule\Components\PageControl;

use App\Model\Destination\Destination;
use App\Model\Destination\DestinationRepository;
use App\Model\Destination\Exceptions\DestinationNotFoundException;

class DataProvider
{

	/**
	 * @var DestinationRepository
	 */
	private $destinationRepository;

	public function __construct(
		DestinationRepository $destinationRepository
	) {
		$this->destinationRepository = $destinationRepository;
	}

	public function getDestinationsForSelect(): array
	{
		$data = [];
		try {
			foreach ($this->destinationRepository->getOrderedByCountryForSelect() as $destination) {
				/** @var Destination $destination */
				$data[$destination->getId()] = $destination->getName();
			}
		} catch (DestinationNotFoundException $exception) {
			//do nothing
		}
		return $data;
	}
}
