<?php
declare(strict_types=1);

namespace App\AdminModule\Components\BlacklistForm;

interface IBlacklistFormFactory
{
	public function create(callable $onSuccess, callable $onError): BlacklistFormControl;
}
