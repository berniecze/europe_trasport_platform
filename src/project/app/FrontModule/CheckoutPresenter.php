<?php

declare(strict_types=1);

namespace App\FrontModule\Presenters;

use App\FrontModule\Components\CartAddressForm\IFormFactory as ICartAddressFormFactory;
use \App\FrontModule\Components\ClientOrderPayment\FormControl as OrderPaymentForm;
use App\FrontModule\Components\ClientOrderPayment\IOrderPaymentFormFactory;
use App\FrontModule\Components\FindRouteFormControl\FormControl;
use App\FrontModule\Components\FindRouteFormControl\IFormFactory;
use App\FrontModule\Components\MainPageNavigation\IMainPageNavigationFactory;
use App\FrontModule\Components\MainPageNavigation\MainPageNavigation;
use App\Model\Cart\Cart;
use App\Model\Cart\CartRepository;
use App\Model\Cart\CartService;
use App\Model\Cart\CartTransportRequest;
use App\Model\Cart\Exceptions\CartNotFoundException;
use App\Model\DefaultData\EmptyDefaultData;
use App\Model\DefaultData\Exceptions\ExpiredCartException;
use App\Model\DefaultData\Exceptions\InvalidCartHashException;
use App\Model\DefaultData\Exceptions\InvalidCartStatusException;
use App\Model\PhotoService;
use App\Model\Transport\Transport;
use App\Model\Transport\TransportPriceCalculator;
use Application\Transport\Request\GetAvailableTransportRequest;
use Application\Transport\UseCase\GetAvailableTransportUseCase;
use DateTimeImmutable;
use Exception;
use Tracy\Debugger;

final class CheckoutPresenter extends BasePresenter
{

	/**
	 * @inject
	 * @var CartRepository $cartRepository
	 */
	public $cartRepository;

	/**
	 * @inject
	 * @var IMainPageNavigationFactory $mainPageNavigationFactory
	 */
	public $mainPageNavigationFactory;

	/**
	 * @inject
	 * @var IOrderPaymentFormFactory $clientOrderPaymentFactory
	 */
	public $clientOrderPaymentFactory;

	/**
	 * @inject
	 * @var IFormFactory $findRouteFormFactory
	 */
	public $findRouteFormFactory;

	/**
	 * @inject
	 * @var ICartAddressFormFactory $cartAddressFormFactory
	 */
	public $cartAddressFormFactory;

	/**
	 * @inject
	 * @var CartService $cartService
	 */
	public $cartService;

	/**
	 * @var string $cartHash
	 */
	private $cartHash;

	/**
	 * @var TransportPriceCalculator $transportPriceCalculator
	 */
	private $transportPriceCalculator;

	/**
	 * @var PhotoService $photoService
	 */
	private $photoService;

	/**
	 * @var GetAvailableTransportUseCase $availableTransportUseCase
	 */
	private $availableTransportUseCase;

	public function __construct(
		TransportPriceCalculator $transportPriceCalculator,
		PhotoService $photoService,
		GetAvailableTransportUseCase $availableTransportUseCase
	) {
		parent::__construct();
		$this->transportPriceCalculator = $transportPriceCalculator;
		$this->photoService = $photoService;
		$this->availableTransportUseCase = $availableTransportUseCase;
	}

	public function actionTransport(): void
	{
		$cartHash = $this->isCartHashValid();
		if ($cartHash === null) {
			$this->getHttpResponse()->deleteCookie(self::USER_COOKIE_CART_HASH);
			$this->flashMessage('Your old order has not been found. Please try to find a new transfer.', 'error');
			$this->redirect(':Front:Homepage:default');
		}

		try {
			$this->defaultDataService->getDefaultData($cartHash);
			$this->cartHash = $cartHash;

			$cart = $this->cartRepository->getByHash($this->cartHash);

			$transports = $this->getTransportForTemplate($cart);
			$this->template->fromDestinationName = $cart->getRoute()->getDeparture()->getName();
			$this->template->toDestinationName = $cart->getRoute()->getArrival()->getName();
			$this->template->transports = $transports;
			$this->template->availableTransports = count($transports);
		} catch (CartNotFoundException $cartNotFoundException) {
			$this->getHttpResponse()->deleteCookie(self::USER_COOKIE_CART_HASH);
			$this->flashMessage('Your old order has not been found. Please try to find a new transfer.', 'error');
			$this->redirect(':Front:Homepage:default');
		} catch (ExpiredCartException $expiredCartException) {
			$this->getHttpResponse()->deleteCookie(self::USER_COOKIE_CART_HASH);
			$this->flashMessage('Your old order is not valid anymore. Please try to find a new transfer.', 'error');
			$this->redirect(':Front:Homepage:default');
		} catch (InvalidCartHashException $invalidCartHashException) {
			$this->redirect(':Front:Homepage:default');
		} catch (InvalidCartStatusException $invalidCartStatusException) {
			$this->getHttpResponse()->deleteCookie(self::USER_COOKIE_CART_HASH);
			$this->redirect(':Front:Homepage:default');
		} catch (\Exception $exception) {
			$this->getHttpResponse()->deleteCookie(self::USER_COOKIE_CART_HASH);
			$this->flashMessage('There has been some technical problem. Please try again later', 'error');
			$this->redirect(':Front:Homepage:default');
		}
	}

	public function actionAddress()
	{
		$cartHash = $this->isCartHashValid();
		if ($cartHash === null) {
			$this->getHttpResponse()->deleteCookie(self::USER_COOKIE_CART_HASH);
			$this->flashMessage('Your old order has not been found. Please try to find a new transfer.', 'error');
			$this->redirect(':Front:Homepage:default');
		}
		try {
			$this->defaultDataService->getDefaultData($cartHash);
			$this->cartHash = $cartHash;
		} catch (\Exception $exception) {
			$this->getHttpResponse()->deleteCookie(self::USER_COOKIE_CART_HASH);
			$this->flashMessage('There has been some technical problem. Please try again later', 'error');
			$this->redirect(':Front:Homepage:default');
		}
	}

	public function actionUseTransport()
	{
		$usedTransport = $this->getRequest()->getParameter('transport');
		$cartHash = $this->isCartHashValid();
		if ($cartHash === null) {
			$this->getHttpResponse()->deleteCookie(self::USER_COOKIE_CART_HASH);
			$this->flashMessage('Your old order has not been found. Please try to find a new transfer.', 'error');
			$this->redirect(':Front:Homepage:default');
		}
		try {
			$this->defaultDataService->getDefaultData($cartHash);

			if ($usedTransport === null) {
				$this->flashMessage('There has been some technical problem. Please try again later', 'error');
			}

			$this->cartHash = $cartHash;
		} catch (\Exception $exception) {
			$this->getHttpResponse()->deleteCookie(self::USER_COOKIE_CART_HASH);
			$this->flashMessage('There has been some technical problem. Please try again later', 'error');
			$this->redirect(':Front:Homepage:default');
		}

		$cartRequest = new CartTransportRequest($cartHash, (int)$usedTransport);
		if (!$this->cartService->addTransportToCart($cartRequest)) {
			$this->flashMessage('Something went wrong. Please try again later', 'error');
			$this->redirect('Checkout:transport');
		}
		$this->redirect('Checkout:address');
	}

	public function createComponentFindRouteForm(): FormControl
	{
		if ($this->cartHash === null) {
			$defaultData = new EmptyDefaultData();
		} else {
			try {
				$defaultData = $this->defaultDataService->getDefaultData($this->cartHash);
			} catch (Exception $exception) {
				$defaultData = new EmptyDefaultData();
			}
		}
		$control = $this->findRouteFormFactory->create($defaultData, FormControl::PAGE_TRANSPORT_CHOOSE);
		$control->onSuccess[] = function (?string $cartHash) {
			$this->getHttpResponse()->setCookie(self::USER_COOKIE_CART_HASH, $cartHash, $this->getCookieExpiration());
			$this->redirect('Checkout:transport');
		};
		$control->onError = function () {
			$this->flashMessage('Something went wrong. Please try again later', 'error');
			$this->redrawControl('findRouteForm');
		};
		return $control;
	}

	public function createComponentMainPageNavigation(): MainPageNavigation
	{
		return $this->mainPageNavigationFactory->create(MainPageNavigation::PAGE_SUB_PAGE_TYPE);
	}

	public function createComponentOrderPaymentForm(): OrderPaymentForm
	{
		$onCreditPayment = function () {
			$this->redirect('ThankYou:default');
		};
		return $this->clientOrderPaymentFactory->create($onCreditPayment);
	}

	public function createComponentCartAddressForm(): \App\FrontModule\Components\CartAddressForm\FormControl
	{
		$cartHash = $this->isCartHashValid();
		$onSuccess = function () {
			$this->redirect('Checkout:confirm');
		};
		$onInvalidCart = function () {
			$this->flashMessage('Your order seems invalid. Please fill your request again.', 'warning');
			$this->redirect('Checkout:address');
		};

		return $this->cartAddressFormFactory->create($onSuccess, $onInvalidCart, $cartHash);
	}

	private function getTransportForTemplate(Cart $cart): array
	{
		$data = [];
		$transportDate = DateTimeImmutable::createFromMutable($cart->getDate());
		$request = new GetAvailableTransportRequest($transportDate, $cart->getPassengers());
		$availableTransport = $this->availableTransportUseCase->execute($request);
		foreach ($availableTransport as $transport) {
			/**
			 * @var Transport $transport
			 */
			$transportData = [
				'id'         => $transport->getId(),
				'name'       => $transport->getName(),
				'capacity'   => $transport->getCapacity(),
				'luggage'    => $transport->getLuggage(),
				'photoUrl'   => $this->photoService->getTransportPhoto($transport->getPhotoUrl()),
				'totalPrice' => $this->transportPriceCalculator->getTotalPrice($cart, $transport),
			];
			$data[$transport->getId()] = $transportData;
		}
		return $data;
	}

	public function renderConfirm(): void
	{
		$cartHash = $this->isCartHashValid();
		if ($cartHash === null) {
			$this->getHttpResponse()->deleteCookie(self::USER_COOKIE_CART_HASH);
			$this->flashMessage('Your old order has not been found. Please try to find a new transfer.', 'error');
			$this->redirect(':Front:Homepage:default');
		}

		try {
			$summaryData = $this->defaultDataService->getSummaryData($cartHash);
			$this->template->setParameters([
				'pickupDate' => $summaryData->getPickupDate(),
				'pickupTime' => $summaryData->getPickupTime(),
				'fromDestination' => $summaryData->getFrom(),
				'toDestination' => $summaryData->getTo(),
				'twoWay' => $summaryData->isTwoWay(),
				'returnDate' => $summaryData->getReturnPickupDate(),
				'returnTime' => $summaryData->getReturnPickupTime(),
				'transportName' => $summaryData->getTransportName(),
				'passengers' => $summaryData->getPassengers(),
				'passengerName' => $summaryData->getClientName(),
				'phoneNumber' => $summaryData->getClientPhone(),
				'finalPrice' => $summaryData->getFinalPrice(),
			]);
		} catch (\Exception $exception) {
			Debugger::log($exception);
			$this->getHttpResponse()->deleteCookie(self::USER_COOKIE_CART_HASH);
			$this->flashMessage('There has been some technical problem. Please try again later', 'error');
			$this->redirect(':Front:Homepage:default');
		}
	}
}
