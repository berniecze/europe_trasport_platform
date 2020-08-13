<?php
declare(strict_types=1);

namespace App\AdminModule\Components\DestinationForm;

use App\Model\Country\CountryRepository;
use App\Model\Country\Exceptions\CountryNotFoundException;
use App\Model\Destination\Destination;
use App\Model\Destination\DestinationService;
use App\Model\Destination\Exceptions\DestinationNotFoundException;

class Handler
{

	/**
	 * @var DestinationService $destinationService
	 */
	private $destinationService;

	/**
	 * @var CountryRepository $countryRepository
	 */
	private $countryRepository;

	public function __construct(DestinationService $destinationService, CountryRepository $countryRepository)
	{
		$this->destinationService = $destinationService;
		$this->countryRepository = $countryRepository;
	}

	/**
	 * @param array $values
	 *
	 * @param string|null $photoFileName
	 *
	 * @throws DestinationNotFoundException
	 * @throws CountryNotFoundException
	 */
	public function handle(array $values, ?string $photoFileName): void
	{
		if (!$values['id']) {
			$destination = new Destination();
		} else {
			$destination = $this->destinationService->getById((int)$values['id']);
		}
		if ($destination === null) {
			throw DestinationNotFoundException::byPrimaryKey($values['id']);
		}
		$country = $this->countryRepository->getById($values['country']);

		$favourites = implode('|', $values['favourites']);
		$destination->setName($values['name']);
		$destination->setActive($values['active']);
		$destination->setDescription($values['description']);
		$destination->setFavourites($favourites);
		$destination->setType($this->sanitizeType($values));
		$destination->setCountry($country);
		if ($photoFileName !== null && $photoFileName !== $destination->getPhoto()) {
			$destination->setPhoto($photoFileName);
		}
		$this->destinationService->save($destination);
	}

	/**
	 * @param array $values
	 *
	 * @return string|null
	 */
	private function sanitizeType(array $values)
	{
		if (!key_exists('type', $values)) {
			return null;
		}
		$type = $values['type'];
		if ($type === Destination::TYPE_AIRPORT_TEXT || $type === Destination::TYPE_CITY_TEXT) {
			return $type;
		}
		return null;
	}
}
