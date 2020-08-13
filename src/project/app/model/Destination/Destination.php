<?php
declare(strict_types=1);

namespace App\Model\Destination;

use App\Model\Country\Country;
use App\Model\Traits\PrimaryKey;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="destination")
 */
class Destination
{

	public const TYPE_AIRPORT_TEXT = 'airport';
	public const TYPE_CITY_TEXT    = 'city';

	use PrimaryKey;

	/**
	 * @var string
	 * @ORM\Column(type="string", length=255, nullable=false)
	 */
	private $name;

	/**
	 * @var string|null
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	private $description;

	/**
	 * @var string|null
	 * @ORM\Column(type="string", length=255, nullable=true)
	 */
	private $photo;

	/**
	 * @var bool
	 * @ORM\Column(type="boolean", nullable=false)
	 */
	private $active;

	/**
	 * @var string|null
	 * @ORM\Column(type="text", nullable=true)
	 */
	private $favourites;

	/**
	 * @var Country
	 * @ORM\ManyToOne(targetEntity="App\Model\Country\Country")
	 * @ORM\JoinColumn(name="country_id", referencedColumnName="id", nullable=false)
	 */
	private $country;

	/**
	 * @var string|null
	 * @ORM\Column(type="text", nullable=true)
	 */
	private $type;

	public function getName(): string
	{
		return $this->name;
	}

	public function setName(string $name): void
	{
		$this->name = $name;
	}

	public function getDescription(): ?string
	{
		return $this->description;
	}

	public function setDescription(?string $description): void
	{
		$this->description = $description;
	}

	public function getPhoto(): ?string
	{
		return $this->photo;
	}

	public function setPhoto(?string $photo): void
	{
		$this->photo = $photo;
	}

	public function isActive(): bool
	{
		return $this->active;
	}

	public function setActive(bool $active): void
	{
		$this->active = $active;
	}

	public function getFavourites(): ?string
	{
		return $this->favourites;
	}

	public function setFavourites(?string $favourites): void
	{
		$this->favourites = $favourites;
	}

	public function getCountry(): Country
	{
		return $this->country;
	}

	public function setCountry(Country $country): void
	{
		$this->country = $country;
	}

	public function getType(): ?string
	{
		return $this->type;
	}

	public function setType(?string $type): void
	{
		$this->type = $type;
	}
}
