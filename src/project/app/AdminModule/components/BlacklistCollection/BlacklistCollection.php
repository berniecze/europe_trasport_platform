<?php
declare(strict_types=1);

namespace App\AdminModule\Components\BlacklistCollection;

use App\Model\Blacklist\Exceptions\BlacklistNotFoundException;
use App\Model\Transport\Exceptions\TransportNotFoundException;
use Nette\Application\UI\Control;

class BlacklistCollection extends Control
{

	/**
	 * @var DataProvider
	 */
	private $dataProvider;

	/**
	 * @var int|null
	 */
	private $selectedTransport;

	public function __construct(DataProvider $dataProvider, ?int $selectedTransport = null)
	{
		parent::__construct();
		$this->dataProvider = $dataProvider;
		$this->selectedTransport = $selectedTransport;
	}

	public function render(): void
	{
		$this->template->setFile(__DIR__ . '/default.latte');

		try {
			if ($this->selectedTransport === null) {
				$collection = $this->dataProvider->getAllBlacklists();
			} else {
				$collection = $this->dataProvider->getForTransport($this->selectedTransport);
			}
			$this->template->blacklists = $collection;
		} catch (\Exception $exception) {
			$this->template->blacklists = [];
		}

		$this->template->render();
	}
}
