<?php
declare(strict_types=1);

namespace App\Model\Country;

use App\Model\Traits\PrimaryKey;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="country")
 */
class Country
{

	use PrimaryKey;

	/**
	 * @var string
	 * @ORM\Column(type="string", length=255, nullable=false)
	 */
	private $name;

	/**
	 * @var string
	 * @ORM\Column(type="string", length=5, nullable=false)
	 */
	private $code;

	public function getName(): string
	{
		return $this->name;
	}

	public function setName(string $name): void
	{
		$this->name = $name;
	}

	public function getCode(): string
	{
		return $this->code;
	}

	public function setCode(string $code): void
	{
		$this->code = $code;
	}
}
