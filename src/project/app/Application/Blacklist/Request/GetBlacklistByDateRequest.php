<?php
declare(strict_types=1);

namespace Application\Blacklist\Request;

use DateTimeImmutable;

class GetBlacklistByDateRequest
{

	/**
	 * @var DateTimeImmutable
	 */
	private $date;

	public function __construct(DateTimeImmutable $date)
	{
		$this->date = $date;
	}

	public function getDate(): DateTimeImmutable
	{
		return $this->date;
	}
}
