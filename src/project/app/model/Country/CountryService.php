<?php
declare(strict_types=1);

namespace App\Model\Country;

use Exception;
use Kdyby\Doctrine\EntityManager;

class CountryService
{

	/**
	 * @var EntityManager $em
	 */
	private $em;

	public function __construct(EntityManager $em)
	{
		$this->em = $em;
	}

	/**
	 * @param Country $country
	 *
	 * @throws Exception
	 */
	public function save(Country $country): void
	{
		$this->em->persist($country);
		$this->em->flush($country);
	}
}
