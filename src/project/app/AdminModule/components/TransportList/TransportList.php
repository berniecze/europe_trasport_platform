<?php
declare(strict_types=1);

namespace App\AdminModule\Components\TransportList;

use Nette\Application\UI\Control;

class TransportList extends Control
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

        $this->template->transports = $this->dataProvider->getAllTransports();

		$this->template->render();
	}
}
