<?php
declare(strict_types=1);

namespace App\AdminModule\Presenters;

use App\AdminModule\Components\RouteForm\RouteFormControl;
use App\AdminModule\Components\RouteForm\RouteFormFactory;
use App\AdminModule\Components\RouteList\IListFactory;
use App\AdminModule\Components\RouteList\ListControl;
use App\Model\Route\RouteService;

class RoutePresenter extends AdminBasePresenter
{

	/**
	 * @var IListFactory
	 */
	private $routeListFactory;

	/**
	 * @var RouteFormFactory
	 */
	private $routeFormFactory;

	/**
	 * @var RouteService
	 */
	private $routeService;

	public function __construct(
		IListFactory $listFactory,
		RouteFormFactory $routeFormFactory,
		RouteService $routeService
	) {
		parent::__construct();
		$this->routeListFactory = $listFactory;
		$this->routeFormFactory = $routeFormFactory;
		$this->routeService = $routeService;
	}

	public function createComponentList(): ListControl
	{
		return $this->routeListFactory->create();
	}

	public function createComponentRouteForm(): RouteFormControl
	{
		$onSuccess = function () {
			$this->flashMessage('Route saved');
			$this->redirect('Route:default');
		};
		$onError = function () {
			$this->flashMessage('An error happened. Please try again later.');
			$this->redirect('this');
		};

		return $this->routeFormFactory->create($onSuccess, $onError);
	}

	public function actionEdit($routeId): void
	{
		if ($routeId === null) {
			$this->flashMessage('Unknown id four route administration');
			$this->redirect('Route:default');
		}
		$route = $this->routeService->getById((int)$routeId);

		if ($route === null) {
			$this->flashMessage('Route does not exists');
			$this->redirect('Route:default');
		}
		$defaultValues = [
			'id' => $route->getId(),
			'departure' => $route->getDeparture()->getId(),
			'arrival' => $route->getArrival()->getId(),
			'active' => (int)$route->isActive(),
			'price' => $route->getPrice(),
			'distance' => $route->getDistance(),
			'duration' => $route->getDuration(),
		];
		$this['routeForm']['form']->setDefaults($defaultValues);
	}

	public function actionDelete($routeId): void
	{
		if ($routeId === null) {
			$this->flashMessage('Unknown id for route administration');
			$this->redirect('Route:default');
		}
		$route = $this->routeService->getById((int)$routeId);

		if ($route === null) {
			$this->flashMessage('Route does not exists');
			$this->redirect('Route:default');
		}

		$this->routeService->remove($route);
		$this->flashMessage('Route successfully removed', 'success');
		$this->redirect('default');

	}
}
