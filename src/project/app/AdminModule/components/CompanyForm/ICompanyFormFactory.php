<?php
declare(strict_types=1);

namespace App\AdminModule\Components\CompanyForm;

interface ICompanyFormFactory
{
	public function create(callable $onSuccess, callable $onError): FormControl;
}
