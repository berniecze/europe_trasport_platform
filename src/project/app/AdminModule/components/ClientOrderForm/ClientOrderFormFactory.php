<?php
declare(strict_types=1);

namespace App\AdminModule\Components\ClientOrderForm;

interface ClientOrderFormFactory
{
	public function create(callable $onSuccess, callable $onError): ClientOrderFormControl;
}
