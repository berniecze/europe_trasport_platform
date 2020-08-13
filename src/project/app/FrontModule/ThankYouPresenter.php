<?php

declare(strict_types=1);

namespace App\FrontModule\Presenters;

use App\FrontModule\Components\MainPageNavigation\IMainPageNavigationFactory;
use App\FrontModule\Components\MainPageNavigation\MainPageNavigation;
use App\Model\Cart\CartRepository;
use App\Model\Cart\CartValidityService;
use App\Model\ClientOrder\ClientOrderRepository;
use App\Model\Company\CompanyRepository;
use App\Model\DateTimeService;
use Nette\Application\BadRequestException;
use Tracy\Debugger;
use Tracy\ILogger;

final class ThankYouPresenter extends BasePresenter
{

	/**
	 * @inject
	 * @var IMainPageNavigationFactory $mainPageNavigationFactory
	 */
	public $mainPageNavigationFactory;

	/**
	 * @inject
	 * @var ClientOrderRepository $clientOrderRepository
	 */
	public $clientOrderRepository;

	/**
	 * @inject
	 * @var CartValidityService $cartValidityService
	 */
	public $cartValidityService;

	/**
	 * @inject
	 * @var DateTimeService $dateTimeService
	 */
	public $dateTimeService;

	/**
	 * @inject
	 * @var CompanyRepository $companyRepository
	 */
	public $companyRepository;

	/**
	 * @inject
	 * @var CartRepository $cartRepository
	 */
	public $cartRepository;

	public function createComponentMainPageNavigation(): MainPageNavigation
	{
		return $this->mainPageNavigationFactory->create(MainPageNavigation::PAGE_SUB_PAGE_TYPE);
	}

	public function actionDefault(): void
	{
		$cartHash = $this->isCartHashValid();
		if ($cartHash === null) {
			$this->getHttpResponse()->deleteCookie(self::USER_COOKIE_CART_HASH);
			$this->flashMessage('Your old order has not been found. Please try to find a new transfer.', 'error');
			$this->redirect(':Front:Homepage:default');
		}

		try {
			$cart = $this->cartRepository->getByHash($cartHash);
			$orderId = $cart->getOrder()->getId();
		} catch (\Exception $exception) {
			Debugger::log($exception, ILogger::CRITICAL);
			$this->flashMessage('There has been some technical problem. Please try again later', 'error');
			$this->redirect(':Front:Homepage:default');
		}

		if ($orderId === null || $orderId === '' || !is_numeric($orderId) || $cart->getOrder() === null) {
			throw new BadRequestException();
		}

		if ($cart->getHash() !== $cartHash) {
			$this->flashMessage('Invalid order detail information', 'error');
			throw new BadRequestException();
		}

		if ($cart->getDate() < $this->dateTimeService->getActualDateTime()) {
			$this->flashMessage('Order has been already finished.', 'error');
			throw new BadRequestException();
		}
	}
}
