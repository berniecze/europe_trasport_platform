<?php
declare(strict_types=1);

namespace App\Model\Page\Exceptions;

use Exception;

class PageNotFoundException extends Exception
{

	public static function byPrimaryKey(int $pageId): self
	{
		return new static(sprintf('Page with id: %d not found', $pageId));
	}
}
