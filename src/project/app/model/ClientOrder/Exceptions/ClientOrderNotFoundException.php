<?php
declare(strict_types=1);

namespace App\Model\ClientOrder\Exceptions;

use Exception;

class ClientOrderNotFoundException extends Exception
{

	public static function byId(int $id): self
	{
		return new static(sprintf('ClientOrder with id: %s not found', $id));
	}
}
