<?php
declare(strict_types=1);

namespace App\Model\Cart;

use App\Model\Client\Client;
use App\Model\ClientOrder\ClientOrder;
use App\Model\Route\Route;
use App\Model\Traits\PrimaryKey;
use App\Model\Transport\Transport;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="cart")
 */
class Cart
{
	public const STATUS_CREATED       = 1;
	public const STATUS_CREATED_ORDER = 2;
	public const STATUS_DONE          = 3;
	public const STATUS_CANCELLED     = 4;

	use PrimaryKey;

	/**
	 * @var Route
	 * @ORM\ManyToOne(targetEntity="App\Model\Route\Route")
	 * @ORM\JoinColumn(name="route_id", referencedColumnName="id", nullable=false)
	 */
	private $route;

	/**
	 * @var \DateTime
	 * @ORM\Column(name="date", type="datetime")
	 */
	private $date;

	/**
	 * @var \DateTime
	 * @ORM\Column(name="time", type="datetime")
	 */
	private $time;

	/**
	 * @var string
	 * @ORM\Column(name="hash", type="string", nullable=false)
	 */
	private $hash;

	/**
	 * @var Transport|null
	 * @ORM\ManyToOne(targetEntity="App\Model\Transport\Transport")
	 * @ORM\JoinColumn(name="transport_id", referencedColumnName="id", nullable=true)
	 */
	private $transport;

	/**
	 * @var Client|null
	 * @ORM\ManyToOne(targetEntity="App\Model\Client\Client")
	 * @ORM\JoinColumn(name="client_id", referencedColumnName="id", nullable=true)
	 */
	private $client;

	/**
	 * @var int|null
	 * @ORM\Column(name="status", type="integer", nullable=true)
	 */
	private $status;

	/**
	 * @var string|null
	 * @ORM\Column(name="notes", type="string", nullable=true)
	 */
	private $notes;

	/**
	 * @ORM\OneToOne(targetEntity="App\Model\ClientOrder\ClientOrder", mappedBy="cart")
	 */
	private $order;

	/**
	 * @var int
	 * @ORM\Column(name="passengers", type="integer", nullable=false)
	 */
	private $passengers;

	public function getRoute(): Route
	{
		return $this->route;
	}

	public function setRoute(Route $route): void
	{
		$this->route = $route;
	}

	public function setDate(\DateTime $date): void
	{
		$this->date = $date;
	}

	public function setHash(string $hash): void
	{
		$this->hash = $hash;
	}

	public function getDate(): \DateTime
	{
		return $this->date;
	}

	public function getHash(): string
	{
		return $this->hash;
	}

	public function getTransport(): ?Transport
	{
		return $this->transport;
	}

	public function setTransport(?Transport $transport): void
	{
		$this->transport = $transport;
	}

	public function getClient(): ?Client
	{
		return $this->client;
	}

	public function setClient(?Client $client): void
	{
		$this->client = $client;
	}

	public function getStatus(): ?int
	{
		return $this->status;
	}

	public function setStatus(?int $status): void
	{
		$this->status = $status;
	}

	public function getNotes(): ?string
	{
		return $this->notes;
	}

	public function setNotes(?string $notes): void
	{
		$this->notes = $notes;
	}

	public function getOrder(): ?ClientOrder
	{
		return $this->order;
	}

	public function getPassengers(): int
	{
		return $this->passengers;
	}

	public function setPassengers(int $passengers): void
	{
		$this->passengers = $passengers;
	}

	public function getTime(): \DateTime
	{
		return $this->time;
	}

	public function setTime(\DateTime $time): void
	{
		$this->time = $time;
	}
}
