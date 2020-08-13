<?php
declare(strict_types=1);

namespace App\AdminModule\Components\RouteList;

use App\Model\Route\Exceptions\RouteNotFoundException;
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

	public function render()
	{
		$this->template->setFile(__DIR__ . '/default.latte');

		try {
			$routes = $this->dataProvider->getAllRoutes();
			$this->template->routes = $routes;
		} catch (RouteNotFoundException $exception) {
			$this->template->routes = [];
		}

		$this->template->render();
	}
}
