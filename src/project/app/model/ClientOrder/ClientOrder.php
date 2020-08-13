<?php
declare(strict_types=1);

namespace App\Model\ClientOrder;

use App\Model\Cart\Cart;
use App\Model\Driver\Driver;
use App\Model\Traits\PrimaryKey;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="client_order")
 */
class ClientOrder
{

	public const ACCEPTED_ORDERS     = 1;
	public const NOT_ACCEPTED_ORDERS = 2;
	public const FINISHED_ORDERS     = 3;
	public const CANCELLED_ORDERS    = 4;

	use PrimaryKey;

	/**
	 * One Cart has One Customer.
	 * @ORM\OneToOne(targetEntity="App\Model\Cart\Cart", inversedBy="order")
	 * @ORM\JoinColumn(name="cart_id", referencedColumnName="id")
	 */
	private $cart;

	/**
	 * @var Driver|null
	 * @ORM\ManyToOne(targetEntity="App\Model\Driver\Driver")
	 * @ORM\JoinColumn(name="driver_id", referencedColumnName="id", nullable=true)
	 */
	private $driver;

	/**
	 * @var string
	 * @ORM\Column(name="number", type="string", nullable=false)
	 */
	private $number;

	/**
	 * @var string
	 * @ORM\Column(name="pay_id", type="string", nullable=false)
	 */
	private $payId;

	/**
	 * @var int
	 * @ORM\Column(type="integer", nullable=false)
	 */
	private $status;

	/**
	 * @var float
	 * @ORM\Column(name="final_price", type="decimal", precision=8, scale=2, nullable=false)
	 */
	private $finalPrice;

	/**
	 * @var \DateTime
	 * @ORM\Column(name="created", type="datetime")
	 */
	private $created;

	public function getCart(): ?Cart
	{
		return $this->cart;
	}

	public function setCart(?Cart $cart): void
	{
		$this->cart = $cart;
	}

	public function getNumber(): string
	{
		return $this->number;
	}

	public function setNumber(string $number): void
	{
		$this->number = $number;
	}

	public function getPayId(): string
	{
		return $this->payId;
	}

	public function setPayId(string $payId): void
	{
		$this->payId = $payId;
	}

	public function getStatus(): int
	{
		return $this->status;
	}

	public function setStatus(int $status): void
	{
		$this->status = $status;
	}

	public function getFinalPrice(): float
	{
		return (float)$this->finalPrice;
	}

	public function setFinalPrice(float $finalPrice): void
	{
		$this->finalPrice = $finalPrice;
	}

	public function getCreated(): \DateTime
	{
		return $this->created;
	}

	public function setCreated(\DateTime $created): void
	{
		$this->created = $created;
	}

	public function getDriver(): ?Driver
	{
		return $this->driver;
	}

	public function setDriver(?Driver $driver): void
	{
		$this->driver = $driver;
	}
}
