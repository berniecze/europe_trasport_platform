<?php
declare(strict_types=1);

namespace App\Model\Company;

use Exception;
use Kdyby\Doctrine\EntityManager;

class CompanyService
{

	/**
	 * @var EntityManager  $em
	 */
	private $em;

	public function __construct(EntityManager $em)
	{
		$this->em = $em;
	}

	/**
	 * @param Company $company
	 *
	 * @throws Exception
	 */
	public function save(Company $company): void
	{
		$this->em->persist($company);
		$this->em->flush($company);
	}
}
