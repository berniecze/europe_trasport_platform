<?php
declare(strict_types=1);

namespace App\FrontModule\Components\ClientOrderPayment;

use Nette\Application\UI\Control;
use Nette\Application\UI\Form;

class FormControl extends Control
{
	const PAYMENT_CARD_OPTION_VALUE = 'card_payment';

	/**
	 * @var callable $onCreditCardPayment
	 */
	private $onCreditCardPayment;

	public function __construct(callable $onCreditCardPayment)
	{
		parent::__construct();
		$this->onCreditCardPayment = $onCreditCardPayment;
	}

	public function render(): void
	{
		$this->template->setFile(__DIR__ . '/form.latte');
		$this->template->render();
	}

	public function createComponentForm(): Form
	{
		$form = new Form();
		$form->addRadioList('payment_option', 'Payment option', [
			self::PAYMENT_CARD_OPTION_VALUE => 'Online with credit card',
		]);
		$form->addSubmit('submit', 'Proceed to pay');
		$form->onSuccess[] = function (Form $form) {
			$this->onFormSuccess($form);
		};
		return $form;
	}

	public function onFormSuccess(Form $form): void
	{
		$values = $form->getValues();
		if ($values->payment_option === self::PAYMENT_CARD_OPTION_VALUE) {
			($this->onCreditCardPayment)();
		}
	}
}
