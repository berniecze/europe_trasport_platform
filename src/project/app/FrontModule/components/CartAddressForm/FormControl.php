<?php
declare(strict_types=1);

namespace App\FrontModule\Components\CartAddressForm;

use App\Model\Cart\Cart;
use App\Model\Cart\Exceptions\InvalidCartException;
use App\Model\Client\ClientRequest;
use App\Model\Destination\Destination;
use App\Model\Transport\TransportPriceCalculator;
use DateTime;
use Nette\Application\UI\Control;
use Nette\Application\UI\Form;

class FormControl extends Control
{

	/**
	 * @var callable $onSuccess
	 */
	private $onSuccess;

	/**
	 * @var callable $onInvalidCart
	 */
	private $onInvalidCart;

	/**
	 * @var bool $twoWaySelected
	 */
	private $twoWaySelected;

	/**
	 * @var string|null $cartHash
	 */
	private $cartHash;

	/**
	 * @var DataProvider $dataProvider
	 */
	private $dataProvider;

	/**
	 * @var Handler $handler
	 */
	private $handler;

	/**
	 * @var TransportPriceCalculator $transportPriceCalculator
	 */
	private $transportPriceCalculator;

	public function __construct(
		callable $onSuccess,
		callable $onInvalidCart,
		?string $cartHash,
		DataProvider $dataProvider,
		Handler $handler,
		TransportPriceCalculator $transportPriceCalculator
	) {
		parent::__construct();
		$this->cartHash = $cartHash;
		$this->handler = $handler;
		$this->dataProvider = $dataProvider;
		$this->onSuccess = $onSuccess;
		$this->onInvalidCart = $onInvalidCart;
		$this->transportPriceCalculator = $transportPriceCalculator;
	}

	public function render(): void
	{
		$cart = $this->getCart();

		if ($cart === null || $cart->getTransport() === null) {
			($this->onInvalidCart)();
		}

		$totalPrice = $this->transportPriceCalculator->getTotalPrice($cart);
		/** @var Cart $cart */
		$this->template->setFile(__DIR__ . '/form.latte');
		$templateData = [
			'date'             => $cart->getDate(),
			'time'             => $cart->getTime(),
			'departureName'    => $cart->getRoute()->getDeparture()->getName(),
			'arrivalName'      => $cart->getRoute()->getArrival()->getName(),
			'price'            => $totalPrice,
			'car'              => $cart->getTransport()->getName(),
			'duration'         => $cart->getRoute()->getDuration(),
			'distance'         => $cart->getRoute()->getDistance(),
			'twoWaySelected'   => $this->twoWaySelected,
			'airportDeparture' => $cart->getRoute()->getDeparture()->getType() === Destination::TYPE_AIRPORT_TEXT ? true : false,
			'airportArrival'   => $cart->getRoute()->getArrival()->getType() === Destination::TYPE_AIRPORT_TEXT ? true : false,
		];
		$this->template->setParameters($templateData);
		$this->template->render();
	}

	public function createComponentForm(): Form
	{
		$form = new Form();
		$form->addText('name', 'First name')
			 ->setRequired('Please tell us your first name');
		$form->addText('lastname', 'Last name')
			 ->setRequired('Please tell us your last name');
		$form->addText('email', 'Email')
			 ->addRule(Form::EMAIL)
			 ->setRequired('Please tell us your email');
		$form->addText('phone', 'Phone number')
			 ->setRequired('Please tell us your phone number');

		$form->addText('transport_ticket', 'Ticket number');

		$form->addCheckboxList('extra_cargo', 'Extra packages', $this->getExtraPackagesForForm());

		$form->addText('from_address', 'Pick up address')
			 ->setRequired('Tell us your where you want to pick up');

		$form->addText('to_address', 'Destination address')
			 ->setRequired('Please tell us your destination address');

		$form->addTextArea('notes');

		$form->addCheckbox('two_way', 'With return trip')
			 ->setAttribute('id', 'two_way_check')
			 ->addCondition($form::EQUAL, true)
			 ->toggle('two_way_options');

		$form->addText('transport_ticket_return', 'Return ticket number');
		$form->addText('return_departure_date', 'Date of departure');
		$form->addText('return_departure_time', 'Time of departure');

		$form->addCheckbox('terms', 'Please accepts our Terms and conditions')
			 ->setRequired('You have to agree with our terms and conditions');

		$form->addSubmit('submit', 'Choose payment option');

		$form->onValidate[] = function () {
			if ($this->getCart() === null) {
				($this->onInvalidCart)();
			}
		};

		$form->onSuccess[] = function (Form $form) {
			$this->onFormSuccess($form);
		};
		return $form;
	}

	private function onFormSuccess(Form $form): void
	{
		/**
		 * @var Cart $cart
		 */
		$cart = $this->getCart();

		$values = $form->getValues();
		$extraCargo = implode('|', $values['extra_cargo']);
		$request = new ClientRequest(
			$values['name'],
			$values['lastname'],
			$values['email'],
			$values['phone'],
			$values['transport_ticket'],
			$extraCargo,
			$values['from_address'],
			$values['to_address'],
			$values['notes']
		);
		//TODO date and time separation
		if ($values['two_way']) {
			$request->setReturnDepartureDate(new DateTime($values['return_departure_date']));
			$request->setReturnDepartureDate(new DateTime($values['return_departure_date']));
			$request->setReturnTickerNumber($values['transport_ticket_return']);
		}
		$orderId = $this->handler->save($request, $cart);
		if ($orderId !== null) {
			($this->onSuccess)($orderId);
		} else {
			($this->onInvalidCart)();
		}
	}

	private function getExtraPackagesForForm(): array
	{
		return [
			'ski'    => 'Ski',
			'infant' => 'Infant seat',
			'seat'   => 'Seat',
		];
	}

	private function getCart(): ?Cart
	{
		try {
			$cart = $this->dataProvider->getValidCart($this->cartHash);
			if ($cart === null) {
				($this->onInvalidCart)();
			}
			return $cart;
		} catch (InvalidCartException $exception) {
			($this->onInvalidCart)();
		}
		return null;
	}

	public function handleTwoWayTrip(): void
	{
		$selected = $this->presenter->getParameter('selected');
		$this->template->twoWaySelected = $selected;
		$this->twoWaySelected = $selected;

		if ($this->presenter->isAjax()) {
			$this->redrawControl('totalPrice');
			$this->redrawControl('returnPriceInfo');
		} else {
			$this->presenter->redirect('this');
		}
	}
}
