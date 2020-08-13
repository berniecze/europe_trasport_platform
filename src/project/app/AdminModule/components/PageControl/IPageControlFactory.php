<?php
declare(strict_types=1);

namespace App\AdminModule\Components\PageControl;

interface IPageControlFactory
{

	public function create(callable $onSuccess, callable $onError): PageControl;
}
