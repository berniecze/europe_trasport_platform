<?php
declare(strict_types=1);

namespace App\FrontModule\Components\FindRouteFormControl;

use App\Model\DefaultData\DefaultDataInterface;

interface IFormFactory
{

	public function create(
		?DefaultDataInterface $customerDefaultData = null,
		string $pageType = FormControl::PAGE_MAIN_TYPE
	): FormControl;
}
