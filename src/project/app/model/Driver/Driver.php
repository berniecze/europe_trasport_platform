<?php
declare(strict_types=1);

namespace App\Model\Driver;

use App\Model\Traits\PrimaryKey;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="driver")
 */
class Driver
{

	use PrimaryKey;

	/**
	 * @var string
	 * @ORM\Column(type="string", length=255, nullable=false)
	 */
	private $surname;

	/**
	 * @var string
	 * @ORM\Column(type="string", length=255, nullable=false)
	 */
	private $phone;

	public function getSurname(): string
	{
		return $this->surname;
	}

	public function setSurname(string $surname): void
	{
		$this->surname = $surname;
	}

	public function getPhone(): string
	{
		return $this->phone;
	}

	public function setPhone(string $phone): void
	{
		$this->phone = $phone;
	}
}
