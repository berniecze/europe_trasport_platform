<?php
declare(strict_types=1);

namespace App\AdminModule\Components\LoginForm;

interface IFormFactory
{

	public function create(callable $onSuccess, callable $onError): FormControl;
}
