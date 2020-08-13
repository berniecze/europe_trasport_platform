<?php
declare(strict_types=1);

namespace Application\Transport\Request;

use DateTimeImmutable;

class GetAvailableTransportRequest
{

	/**
	 * @var int
	 */
	private $passengers;

	/**
	 * @var DateTimeImmutable
	 */
	private $pickupDate;

	public function __construct(DateTimeImmutable $pickupDate, int $passengers)
	{
		$this->pickupDate = $pickupDate;
		$this->passengers = $passengers;
	}

	public function getPassengers(): int
	{
		return $this->passengers;
	}

	public function getPickupDate(): DateTimeImmutable
	{
		return $this->pickupDate;
	}
}
