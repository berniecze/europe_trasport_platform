<?php
declare(strict_types=1);

namespace App\Model\Transport;

use App\Model\Traits\PrimaryKey;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="transport")
 */
class Transport
{
	use PrimaryKey;

	/**
	 * @var bool
	 * @ORM\Column(type="boolean", nullable=false)
	 */
	private $active;

	/**
	 * @var string
	 * @ORM\Column(type="string", length=255, nullable=false)
	 */
	private $name;

	/**
	 * @var string|null
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	private $photoUrl;

	/**
	 * @var int
	 * @ORM\Column(type="integer", length=11, nullable=false)
	 */
	private $capacity;

	/**
	 * @var int
	 * @ORM\Column(type="integer", length=11, nullable=false)
	 */
	private $luggage;

	/**
	 * @var float
	 * @ORM\Column(name="fixed_price", type="decimal", precision=8, scale=2, nullable=false)
	 */
	private $fixedPrice;

	/**
	 * @var float
	 * @ORM\Column(name="multiplier_price", type="decimal", precision=8, scale=2, nullable=false)
	 */
	private $multiplierPrice;

	/**
	 * @var string|null
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	private $description;

	public function isActive(): bool
	{
		return $this->active;
	}

	public function setActive(bool $active): void
	{
		$this->active = $active;
	}

	public function getName(): string
	{
		return $this->name;
	}

	public function setName(string $name): void
	{
		$this->name = $name;
	}

	public function getPhotoUrl(): ?string
	{
		return $this->photoUrl;
	}

	public function setPhotoUrl(string $photoUrl): void
	{
		$this->photoUrl = $photoUrl;
	}

	public function getCapacity(): int
	{
		return $this->capacity;
	}

	public function setCapacity(int $capacity): void
	{
		$this->capacity = $capacity;
	}

	public function getLuggage(): int
	{
		return $this->luggage;
	}

	public function setLuggage(int $luggage): void
	{
		$this->luggage = $luggage;
	}

	public function getFixedPrice(): float
	{
		return (float)$this->fixedPrice;
	}

	public function setFixedPrice(float $fixedPrice): void
	{
		$this->fixedPrice = $fixedPrice;
	}

	public function getMultiplierPrice(): float
	{
		return (float)$this->multiplierPrice;
	}

	public function setMultiplierPrice(float $multiplierPrice): void
	{
		$this->multiplierPrice = $multiplierPrice;
	}

	public function getDescription(): ?string
	{
		return $this->description;
	}

	public function setDescription(?string $description): void
	{
		$this->description = $description;
	}
}
