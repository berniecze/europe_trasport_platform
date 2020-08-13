<?php
declare(strict_types=1);

namespace App\AdminModule\Components\RouteForm;

interface RouteFormFactory
{

	public function create(callable $onSuccess, callable $onError): RouteFormControl;
}
