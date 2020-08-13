<?php
declare(strict_types=1);

namespace App\Model\Route\Exceptions;

use Exception;

class RouteNotFoundException extends Exception
{

	public static function byPrimaryKey(int $routeId): self
	{
		return new static(sprintf('Route with id: %d not found', $routeId));
	}
}
