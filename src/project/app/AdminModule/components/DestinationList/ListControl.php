<?php
declare(strict_types=1);

namespace App\AdminModule\Components\DestinationList;

use App\Model\Destination\Exceptions\DestinationNotFoundException;
use Nette\Application\UI\Control;

class ListControl extends Control
{

	/**
	 * @var DataProvider
	 */
	private $dataProvider;

	public function __construct(DataProvider $dataProvider)
	{
		parent::__construct();
		$this->dataProvider = $dataProvider;
	}

	public function render(): void
	{
		$this->template->setFile(__DIR__ . '/default.latte');

		try {
			$destinations = $this->dataProvider->getAllDestinations();
			$this->template->destinations = $destinations;
		} catch (DestinationNotFoundException $exception) {
			$this->template->destinations = [];
		}

		$this->template->render();
	}
}
