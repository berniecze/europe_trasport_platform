<?php
declare(strict_types=1);

namespace App\AdminModule\Components\CompanyForm;

interface ICompanyRequest
{
	public function getId(): int;

	public function getName(): string;

	public function getAddress(): string;

	public function getIdentificationNumber(): string;

	public function getTax(): string;
}
