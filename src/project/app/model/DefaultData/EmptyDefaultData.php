<?php
declare(strict_types=1);

namespace App\Model\DefaultData;

use DateTime;

class EmptyDefaultData implements DefaultDataInterface
{

	public function getCartHash(): ?string
	{
		return null;
	}

	public function getDepartureDate(): ?DateTime
	{
		return null;
	}

	public function getDepartureTime(): ?DateTime
	{
		return null;
	}

	public function getFromId(): ?int
	{
		return null;
	}

	public function getPassengers(): ?int
	{
		return null;
	}

	public function getToId(): ?int
	{
		return null;
	}
}
