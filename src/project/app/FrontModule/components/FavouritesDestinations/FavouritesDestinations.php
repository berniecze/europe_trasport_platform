<?php
declare(strict_types=1);

namespace App\FrontModule\Components\FavouritesDestinations;

use App\Model\Destination\Destination;
use App\Model\Destination\Exceptions\DestinationNotFoundException;
use App\Model\PhotoService;
use Nette\Application\UI\Control;

class FavouritesDestinations extends Control
{

	/**
	 * @var DataProvider $dataProvider
	 */
	private $dataProvider;

	/**
	 * @var PhotoService $photoService
	 */
	private $photoService;

	/**
	 * @var int $destinationId
	 */
	private $destinationId;

	public function __construct(DataProvider $dataProvider, PhotoService $photoService, int $destinationId)
	{
		parent::__construct();
		$this->dataProvider = $dataProvider;
		$this->destinationId = $destinationId;
		$this->photoService = $photoService;
	}

	public function render()
	{
		$template = $this->getTemplate();
		$template->setFile(__DIR__ . '/default.latte');

		try {
			$favouriteDestinations = $this->dataProvider->getFavouritesDestinationsById($this->destinationId);
			$data = [];
			foreach ($favouriteDestinations as $favouriteDestination) {
				/** @var Destination $favouriteDestination */
				$data[$favouriteDestination->getId()] = [
					'name'  => $favouriteDestination->getName(),
					'photo' => $this->photoService->getDestinationPhoto($favouriteDestination->getPhoto()),
				];
			}
		} catch (DestinationNotFoundException $exception) {
			$data = [];
		}
		$this->template->destinations = $data;
		$template->render();
	}
}
