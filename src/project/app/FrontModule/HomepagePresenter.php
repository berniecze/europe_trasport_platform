<?php

declare(strict_types=1);

namespace App\FrontModule\Presenters;

use App\FrontModule\Components\FindRouteFormControl\FormControl;
use App\FrontModule\Components\FindRouteFormControl\IFormFactory;
use App\FrontModule\Components\MainPageNavigation\IMainPageNavigationFactory;
use App\FrontModule\Components\MainPageNavigation\MainPageNavigation;
use App\Model\Cart\CartValidityService;
use App\Model\DefaultData\EmptyDefaultData;
use Exception;

final class HomepagePresenter extends BasePresenter
{

	/**
	 * @inject
	 * @var IFormFactory $findRouteFormFactory
	 */
	public $findRouteFormFactory;

	/**
	 * @inject
	 * @var IMainPageNavigationFactory $mainPageNavigationFactory
	 */
	public $mainPageNavigationFactory;

	/**
	 * @inject
	 * @var CartValidityService $cartValidityService
	 */
	public $cartValidityService;

	public function beforeRender()
	{
		parent::beforeRender();
		$savedCartHash = $this->isCartHashValid();
		if ($savedCartHash !== null) {
			try {
				$this->defaultDataService->getDefaultData($savedCartHash);
			} catch (Exception $exception) {
				$this->getHttpResponse()->deleteCookie(self::USER_COOKIE_CART_HASH);
			}
		} else {
			$this->getHttpResponse()->deleteCookie(self::USER_COOKIE_CART_HASH);
		}
	}

	public function createComponentFindRouteForm(): FormControl
	{
		$savedCartHash = $this->isCartHashValid();
		if ($savedCartHash === null) {
			$defaultData = new EmptyDefaultData();
		} else {
			try {
				$defaultData = $this->defaultDataService->getDefaultData($savedCartHash);
			} catch (Exception $exception) {
				$defaultData = new EmptyDefaultData();
			}
		}
		$control = $this->findRouteFormFactory->create($defaultData, FormControl::PAGE_MAIN_TYPE);
		$control->onSuccess[] = function (string $cartHash) {
			$this->getHttpResponse()->setCookie(self::USER_COOKIE_CART_HASH, $cartHash, $this->getCookieExpiration());
			$this->redirect('Checkout:transport');
		};
		$control->onError[] = function () {
			$this->flashMessage('Something went wrong. Please try again later', 'error');
			$this->redrawControl('findRouteForm');
		};
		return $control;
	}

	public function createComponentMainPageNavigation(): MainPageNavigation
	{
		return $this->mainPageNavigationFactory->create();
	}
}
