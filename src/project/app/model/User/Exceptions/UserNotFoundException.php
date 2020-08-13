<?php
declare(strict_types=1);

namespace App\Model\User\Exceptions;

use Exception;

class UserNotFoundException extends Exception
{

	public static function byEmail(string $email): self
	{
		return new static(sprintf('User with email: %s not found', $email));
	}

	public static function byUsername(string $username): self
	{
		return new static(sprintf('User with username: %s not found', $username));
	}
}
