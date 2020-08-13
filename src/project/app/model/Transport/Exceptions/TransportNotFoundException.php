<?php
declare(strict_types=1);

namespace App\Model\Transport\Exceptions;

use Exception;

class TransportNotFoundException extends Exception
{

	public static function byPrimaryKey(int $transportId): self
	{
		return new static(sprintf('Transport with id: %d not found', $transportId));
	}
}
