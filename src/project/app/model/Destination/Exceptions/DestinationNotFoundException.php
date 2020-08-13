<?php
declare(strict_types=1);

namespace App\Model\Destination\Exceptions;

use Exception;

class DestinationNotFoundException extends Exception
{

	public static function byPrimaryKey(int $destinationId): self
	{
		return new static(sprintf('Destination with id: %d not found', $destinationId));
	}
}
