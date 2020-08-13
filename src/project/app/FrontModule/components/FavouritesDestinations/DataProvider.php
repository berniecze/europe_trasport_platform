<?php
declare(strict_types=1);

namespace App\FrontModule\Components\FavouritesDestinations;

use App\Model\Destination\DestinationRepository;
use App\Model\Destination\Exceptions\DestinationNotFoundException;
use Kdyby\Doctrine\ResultSet;

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

	/**
	 * @param int $destinationId
	 *
	 * @return ResultSet
	 * @throws DestinationNotFoundException
	 */
	public function getFavouritesDestinationsById(int $destinationId): ResultSet
	{
		$destination = $this->destinationRepository->getById($destinationId);
		if ($destination->getFavourites() !== null) {
			$favouritesIds = explode('|', $destination->getFavourites());
		} else {
			$favouritesIds = [];
		}
		if (count($favouritesIds) === 0) {
			throw new DestinationNotFoundException();
		}

		return $this->destinationRepository->getByIds($favouritesIds);
	}
}
