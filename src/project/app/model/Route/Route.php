<?php
declare(strict_types=1);

namespace App\Model\Route;

use App\Model\Destination\Destination;
use App\Model\Traits\PrimaryKey;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="route")
 */
class Route
{
	use PrimaryKey;

	/**
	 * @var Destination
	 * @ORM\ManyToOne(targetEntity="App\Model\Destination\Destination")
	 * @ORM\JoinColumn(name="departure_id", referencedColumnName="id", nullable=false)
	 */
	private $departure;

	/**
	 * @var Destination
	 * @ORM\ManyToOne(targetEntity="App\Model\Destination\Destination")
	 * @ORM\JoinColumn(name="arrival_id", referencedColumnName="id", nullable=false)
	 */
	private $arrival;

	/**
	 * @var float
	 * @ORM\Column(name="price", type="decimal", nullable=false)
	 */
	private $price;

	/**
	 * @var bool
	 * @ORM\Column(type="boolean", nullable=false)
	 */
	private $active = false;

	/**
	 * @var float
	 * @ORM\Column(name="distance", type="decimal", nullable=false)
	 */
	private $distance;

	/**
	 * @var string
	 * @ORM\Column(name="duration", type="string", nullable=false)
	 */
	private $duration;

	public function getDeparture(): Destination
	{
		return $this->departure;
	}

	public function setDeparture(Destination $departure): void
	{
		$this->departure = $departure;
	}

	public function getArrival(): Destination
	{
		return $this->arrival;
	}

	public function setArrival(Destination $arrival): void
	{
		$this->arrival = $arrival;
	}

	public function getPrice(): float
	{
		return (float)$this->price;
	}

	public function setPrice(float $price): void
	{
		$this->price = $price;
	}

	public function isActive(): bool
	{
		return $this->active;
	}

	public function setActive(bool $active): void
	{
		$this->active = $active;
	}

	public function getDistance(): float
	{
		return (float)$this->distance;
	}

	public function setDistance(float $distance): void
	{
		$this->distance = $distance;
	}

	public function getDuration(): string
	{
		return $this->duration;
	}

	public function setDuration(string $duration): void
	{
		$this->duration = $duration;
	}
}
