<?php
declare(strict_types=1);

namespace App\Model\DefaultData;

use DateTime;

interface DefaultDataInterface
{
	public function getFromId(): ?int;

	public function getToId(): ?int;

	public function getPassengers(): ?int;

	public function getDepartureDate(): ?DateTime;

	public function getDepartureTime(): ?DateTime;

	public function getCartHash(): ?string;
}
