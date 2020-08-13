<?php
declare(strict_types=1);

namespace App\Model\Client\Exceptions;

use Exception;

class ClientNotFoundException extends Exception
{

	public static function byHash(string $hash): self
	{
		return new static(sprintf('Client with hash: %s not found', $hash));
	}
}
