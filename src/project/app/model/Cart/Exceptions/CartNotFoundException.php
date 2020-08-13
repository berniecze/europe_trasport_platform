<?php
declare(strict_types=1);

namespace App\Model\Cart\Exceptions;

use Exception;

class CartNotFoundException extends Exception
{

	public static function byHash(string $hash): self
	{
		return new static(sprintf('Cart with hash: %s not found', $hash));
	}
}
