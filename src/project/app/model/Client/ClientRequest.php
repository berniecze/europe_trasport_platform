<?php
declare(strict_types=1);

namespace App\Model\Client;

use DateTime;

class ClientRequest
{
	/**
	 * @var string
	 */
	private $name;

	/**
	 * @var string
	 */
	private $lastname;

	/**
	 * @var string
	 */
	private $email;

	/**
	 * @var string
	 */
	private $phone;

	/**
	 * @var string
	 */
	private $ticketNumber;

	/**
	 * @var string
	 */
	private $extraCargo;

	/**
	 * @var string
	 */
	private $fromAddress;

	/**
	 * @var string
	 */
	private $toAddress;

	/**
	 * @var string|null
	 */
	private $notes;

	/**
	 * @var \DateTime
	 */
	private $created;

	/**
	 * @var string|null
	 */
	private $returnTickerNumber;

	/**
	 * @var Datetime|null
	 */
	private $returnDepartureDate;

	public function __construct(
		string $name,
		string $lastname,
		string $email,
		string $phone,
		string $ticketNumber,
		string $extraCargo,
		string $fromAddress,
		string $toAddress,
		?string $notes
	) {
		$this->name = $name;
		$this->lastname = $lastname;
		$this->email = $email;
		$this->phone = $phone;
		$this->ticketNumber = $ticketNumber;
		$this->extraCargo = $extraCargo;
		$this->fromAddress = $fromAddress;
		$this->toAddress = $toAddress;
		$this->created = new \DateTime();
		$this->notes = $notes;
	}

	public function getName(): string
	{
		return $this->name;
	}

	public function getLastname(): string
	{
		return $this->lastname;
	}

	public function getEmail(): string
	{
		return $this->email;
	}

	public function getPhone(): string
	{
		return $this->phone;
	}

	public function getTicketNumber(): string
	{
		return $this->ticketNumber;
	}

	public function getExtraCargo(): string
	{
		return $this->extraCargo;
	}

	public function getFromAddress(): string
	{
		return $this->fromAddress;
	}

	public function getToAddress(): string
	{
		return $this->toAddress;
	}

	public function getCreated(): \DateTime
	{
		return $this->created;
	}

	public function getNotes(): ?string
	{
		return $this->notes;
	}

	public function getReturnTickerNumber(): ?string
	{
		return $this->returnTickerNumber;
	}

	public function setReturnTickerNumber(?string $returnTickerNumber): void
	{
		$this->returnTickerNumber = $returnTickerNumber;
	}

	public function getReturnDepartureDatetime(): ?DateTime
	{
		return $this->returnDepartureDate;
	}

	public function setReturnDepartureDate(?DateTime $returnDepartureDate): void
	{
		$this->returnDepartureDate = $returnDepartureDate;
	}
}
