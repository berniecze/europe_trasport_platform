<?php
declare(strict_types=1);

namespace App\Model\Company\Exceptions;

use Exception;

class CompanyNotFoundException extends Exception
{

	public static function byId(int $id): self
	{
		return new static(sprintf('Company with id: %s not found', $id));
	}
}
