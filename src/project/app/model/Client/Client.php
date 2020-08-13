<?php
declare(strict_types=1);

namespace App\Model\Client;

use App\Model\Traits\PrimaryKey;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="client")
 */
class Client
{
	use PrimaryKey;

	/**
	 * @var string
	 * @ORM\Column(name="name", type="string", nullable=false)
	 */
	private $name;

	/**
	 * @var string
	 * @ORM\Column(name="lastname", type="string", nullable=false)
	 */
	private $lastname;

	/**
	 * @var string
	 * @ORM\Column(name="email", type="string", nullable=false)
	 */
	private $email;

	/**
	 * @var string
	 * @ORM\Column(name="phone", type="string", nullable=false)
	 */
	private $phone;

	/**
	 * @var string
	 * @ORM\Column(name="ticket_number", type="string", nullable=false)
	 */
	private $ticketNumber;

	/**
	 * @var string
	 * @ORM\Column(name="extra_cargo", type="string", nullable=false)
	 */
	private $extraCargo;

	/**
	 * @var string
	 * @ORM\Column(name="from_address", type="string", nullable=false)
	 */
	private $fromAddress;

	/**
	 * @var string
	 * @ORM\Column(name="to_address", type="string", nullable=false)
	 */
	private $toAddress;

	/**
	 * @var DateTime
	 * @ORM\Column(name="created", type="datetime", nullable=false)
	 */
	private $created;

	/**
	 * @var string|null
	 * @ORM\Column(name="transport_ticket_return", type="string", nullable=true)
	 */
	private $transportTicketReturn;

	/**
	 * @var DateTime|null
	 * @ORM\Column(name="return_departure_datetime", type="datetime", nullable=true)
	 */
	private $returnDepartureDatetime;

	public function getName(): string
	{
		return $this->name;
	}

	public function setName(string $name): void
	{
		$this->name = $name;
	}

	public function getLastname(): string
	{
		return $this->lastname;
	}

	public function setLastname(string $lastname): void
	{
		$this->lastname = $lastname;
	}

	public function getEmail(): string
	{
		return $this->email;
	}

	public function setEmail(string $email): void
	{
		$this->email = $email;
	}

	public function getPhone(): string
	{
		return $this->phone;
	}

	public function setPhone(string $phone): void
	{
		$this->phone = $phone;
	}

	public function getTicketNumber(): string
	{
		return $this->ticketNumber;
	}

	public function setTicketNumber(string $ticketNumber): void
	{
		$this->ticketNumber = $ticketNumber;
	}

	public function getExtraCargo(): string
	{
		return $this->extraCargo;
	}

	public function setExtraCargo(string $extraCargo): void
	{
		$this->extraCargo = $extraCargo;
	}

	public function getFromAddress(): string
	{
		return $this->fromAddress;
	}

	public function setFromAddress(string $fromAddress): void
	{
		$this->fromAddress = $fromAddress;
	}

	public function getToAddress(): string
	{
		return $this->toAddress;
	}

	public function setToAddress(string $toAddress): void
	{
		$this->toAddress = $toAddress;
	}

	public function getCreated(): DateTime
	{
		return $this->created;
	}

	public function setCreated(DateTime $created): void
	{
		$this->created = $created;
	}

	public function getTransportTicketReturn(): ?string
	{
		return $this->transportTicketReturn;
	}

	public function setTransportTicketReturn(?string $transportTicketReturn): void
	{
		$this->transportTicketReturn = $transportTicketReturn;
	}

	public function getReturnDepartureDatetime(): ?DateTime
	{
		return $this->returnDepartureDatetime;
	}

	public function setReturnDepartureDatetime(?DateTime $returnDepartureDatetime): void
	{
		$this->returnDepartureDatetime = $returnDepartureDatetime;
	}
}
