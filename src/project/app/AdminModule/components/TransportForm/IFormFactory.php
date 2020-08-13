<?php
declare(strict_types=1);

namespace App\AdminModule\Components\TransportForm;

interface IFormFactory
{

	public function create(callable $onSuccess, callable $onError): TransportFormControl;
}
