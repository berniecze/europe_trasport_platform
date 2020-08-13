<?php
declare(strict_types=1);

namespace App\AdminModule\Components\ChooseDriverForm;

interface ChooseDriverFormFactory
{

	public function create(callable $onSuccess, callable $onError): ChooseDriverFormControl;
}
