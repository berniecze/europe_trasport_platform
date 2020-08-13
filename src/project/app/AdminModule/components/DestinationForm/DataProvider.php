<?php
declare(strict_types=1);

namespace App\AdminModule\Components\DestinationForm;

use App\Model\Country\Country;
use App\Model\Country\CountryRepository;
use App\Model\Country\Exceptions\CountryNotFoundException;
use App\Model\Destination\Destination;
use App\Model\Destination\DestinationRepository;
use App\Model\Destination\Exceptions\DestinationNotFoundException;

class DataProvider
{

	/**
	 * @var DestinationRepository
	 */
	private $destinationRepository;

	/**
	 * @var CountryRepository
	 */
	private $countryRepository;

	public function __construct(DestinationRepository $destinationRepository, CountryRepository $countryRepository)
	{
		$this->destinationRepository = $destinationRepository;
		$this->countryRepository = $countryRepository;
	}

	public function getDestinations(): array
	{
		try {
			$destinations = $this->destinationRepository->getAll();
			$data = [];
			foreach ($destinations as $destination) {
				/** @var Destination $destination */
				$data[$destination->getId()] = $destination->getName();
			}
			return $data;
		} catch (DestinationNotFoundException $exception) {
			return [];
		}
	}

	public function getCountryListForSelect(): array
	{
		$countries = [];
		try {
			foreach ($this->countryRepository->getAll() as $country) {
				/** @var Country $country */
				$countries[$country->getId()] = $country->getName();
			}
			return $countries;
		} catch (CountryNotFoundException $exception) {
			return [];
		}
	}
}
