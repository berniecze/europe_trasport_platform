<?php
declare(strict_types=1);

namespace App\Model\Country\Exceptions;

use Exception;

class CountryNotFoundException extends Exception
{

	public static function byId(int $id): self
	{
		return new static(sprintf('Country with id: %s not found', $id));
	}
}
