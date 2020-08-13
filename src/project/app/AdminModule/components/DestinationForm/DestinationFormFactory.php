<?php
declare(strict_types=1);

namespace App\AdminModule\Components\DestinationForm;

interface DestinationFormFactory
{

	public function create(callable $onSuccess, callable $onError): DestinationFormControl;
}
