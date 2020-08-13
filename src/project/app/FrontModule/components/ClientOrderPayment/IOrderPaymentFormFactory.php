<?php
declare(strict_types=1);

namespace App\FrontModule\Components\ClientOrderPayment;

interface IOrderPaymentFormFactory
{
	public function create(callable $onCreditCardPayment): FormControl;
}
