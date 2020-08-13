<?php
declare(strict_types=1);

namespace App\Model\DefaultData;

class CustomerDefaultData implements DefaultDataInterface
{

	/**
	 * @var int
	 */
	private $fromId;

	/**
	 * @var int
	 */
	private $toId;

	/**
	 * @var int
	 */
	private $passengers;

	/**
	 * @var \DateTime
	 */
	private $departureDate;

	/**
	 * @var \DateTime
	 */
	private $departureTime;

	/**
	 * @var string
	 */
	private $cartHash;

	public function __construct(
		?int $fromId,
		?int $toId,
		int $passengers,
		\DateTime $departureDate,
		\DateTime $departureTime,
		string $cartHash
	) {
		$this->fromId = $fromId;
		$this->toId = $toId;
		$this->passengers = $passengers;
		$this->departureDate = $departureDate;
		$this->departureTime = $departureTime;
		$this->cartHash = $cartHash;
	}

	public function getFromId(): ?int
	{
		return $this->fromId;
	}

	public function getToId(): ?int
	{
		return $this->toId;
	}

	public function getPassengers(): ?int
	{
		return $this->passengers;
	}

	public function getDepartureDate(): \DateTime
	{
		return $this->departureDate;
	}

	public function getDepartureTime(): \DateTime
	{
		return $this->departureTime;
	}

	public function getCartHash(): string
	{
		return $this->cartHash;
	}
}
