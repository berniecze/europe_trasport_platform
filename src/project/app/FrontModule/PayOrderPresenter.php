<?php
declare(strict_types=1);

namespace App\FrontModule\Presenters;

use App\FrontModule\Components\MainPageNavigation\IMainPageNavigationFactory;
use App\FrontModule\Components\MainPageNavigation\MainPageNavigation;
use App\Model\Cart\CartValidityService;

final class PayOrderPresenter extends BasePresenter
{

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
		$savedCartHash = $this->getHttpRequest()->getCookie(self::USER_COOKIE_CART_HASH);

		if ($savedCartHash !== null && !$this->cartValidityService->isValid($savedCartHash)) {
			$this->getHttpResponse()->deleteCookie(self::USER_COOKIE_CART_HASH);
		}
	}

	public function createComponentMainPageNavigation(): MainPageNavigation
	{
		return $this->mainPageNavigationFactory->create();
	}
}
