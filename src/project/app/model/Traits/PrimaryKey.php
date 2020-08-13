<?php

namespace App\Model\Traits;

trait PrimaryKey
{

	/**
	 * @var int
	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue
	 */
	private $id;

	public function getId(): int
	{
		return $this->id;
	}

	public function __clone()
	{
		$this->id = null;
	}
}
