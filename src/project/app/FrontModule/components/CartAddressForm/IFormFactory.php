<?php
declare(strict_types=1);

namespace App\FrontModule\Components\CartAddressForm;

interface IFormFactory
{

	public function create(callable $onSuccess, callable $onInvalidCart, ?string $cartHash): FormControl;
}
