<?php
declare(strict_types=1);

namespace App\Model\Company;

use App\Model\Traits\PrimaryKey;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="company")
 */
class Company
{
	use PrimaryKey;

	/**
	 * @var string
	 * @ORM\Column(name="name", type="string", nullable=false)
	 */
	private $name;

	/**
	 * @var string
	 * @ORM\Column(name="address", type="string", nullable=false)
	 */
	private $address;

	/**
	 * @var string
	 * @ORM\Column(name="identification_number", type="string", nullable=false)
	 */
	private $identificationNumber;

	/**
	 * @var string
	 * @ORM\Column(name="tax", type="string", nullable=false)
	 */
	private $tax;

	public function getName(): string
	{
		return $this->name;
	}

	public function setName(string $name): void
	{
		$this->name = $name;
	}

	public function getAddress(): string
	{
		return $this->address;
	}

	public function setAddress(string $address): void
	{
		$this->address = $address;
	}

	public function getIdentificationNumber(): string
	{
		return $this->identificationNumber;
	}

	public function setIdentificationNumber(string $identificationNumber): void
	{
		$this->identificationNumber = $identificationNumber;
	}

	public function getTax(): string
	{
		return $this->tax;
	}

	public function setTax(string $tax): void
	{
		$this->tax = $tax;
	}
}
