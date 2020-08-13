<?php
declare(strict_types=1);

namespace App\AdminModule\Components\DestinationList;

use App\Model\Destination\Destination;
use App\Model\Destination\DestinationRepository;
use App\Model\Destination\Exceptions\DestinationNotFoundException;
use Kdyby\Doctrine\ResultSet;

class DataProvider
{

	/**
	 * @var DestinationRepository
	 */
	private $repository;

	public function __construct(DestinationRepository $repository)
	{
		$this->repository = $repository;
	}

	/**
	 * @return Destination[]|ResultSet
	 * @throws DestinationNotFoundException
	 */
	public function getAllDestinations()
	{
		try {
			return $this->repository->getAll();
		} catch (DestinationNotFoundException $exception) {
			throw $exception;
		}
	}
}
