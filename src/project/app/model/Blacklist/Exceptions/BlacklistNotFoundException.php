<?php
declare(strict_types=1);

namespace App\Model\Blacklist\Exceptions;

use Exception;

class BlacklistNotFoundException extends Exception
{
	public static function byPrimaryKey(int $blacklistId): self
	{
		return new static(sprintf('Blacklist with id: %d not found', $blacklistId));
	}
}
