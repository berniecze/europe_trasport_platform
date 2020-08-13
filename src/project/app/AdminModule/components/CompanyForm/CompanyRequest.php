<?php
declare(strict_types=1);

namespace App\AdminModule\Components\CompanyForm;

class CompanyRequest implements ICompanyRequest
{

	/**
	 * @var int $id
	 */
	private $id;

	/**
	 * @var string $name
	 */
	private $name;

	/**
	 * @var string $address
	 */
	private $address;

	/**
	 * @var string $identificationNumber
	 */
	private $identificationNumber;

	/**
	 * @var string $tax
	 */
	private $tax;

	public function __construct(int $id, string $name, string $address, string $identificationNumber)
	{
		$this->id = $id;
		$this->name = $name;
		$this->address = $address;
		$this->identificationNumber = $identificationNumber;
		$this->tax = $identificationNumber;
	}

	public function getId(): int
	{
		return $this->id;
	}

	public function getName(): string
	{
		return $this->name;
	}

	public function getAddress(): string
	{
		return $this->address;
	}

	public function getIdentificationNumber(): string
	{
		return $this->identificationNumber;
	}

	public function getTax(): string
	{
		return $this->tax;
	}
}
