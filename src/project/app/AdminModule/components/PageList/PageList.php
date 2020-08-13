<?php
declare(strict_types=1);

namespace App\AdminModule\Components\PageList;

use App\Model\Page\Exceptions\PageNotFoundException;
use Nette\Application\UI\Control;

class PageList extends Control
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

	public function render()
	{
		$this->template->setFile(__DIR__ . '/default.latte');

		try {
			$pages = $this->dataProvider->getAllPages();
		} catch (PageNotFoundException $exception) {
			$pages = [];
		}

		$this->template->pages = $pages;
		$this->template->render();
	}
}
