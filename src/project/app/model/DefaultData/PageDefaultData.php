<?php
declare(strict_types=1);

namespace App\Model\DefaultData;

use DateTime;

class PageDefaultData implements DefaultDataInterface
{

	/**
	 * @var int|null
	 */
	private $fromId;

	/**
	 * @var int|null
	 */
	private $toId;

	public function __construct(?int $fromId, ?int $toId)
	{
		$this->fromId = $fromId;
		$this->toId = $toId;
	}

	public function getPassengers(): ?int
	{
		return null;
	}

	public function getFromId(): ?int
	{
		return $this->fromId;
	}

	public function getDepartureDate(): ?DateTime
	{
		return null;
	}

	public function getDepartureTime(): ?DateTime
	{
		return null;
	}

	public function getCartHash(): ?string
	{
		return null;
	}

	public function getToId(): ?int
	{
		return $this->toId;
	}
}
