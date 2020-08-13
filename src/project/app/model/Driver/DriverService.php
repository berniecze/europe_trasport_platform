<?php
declare(strict_types=1);

namespace App\Model\Driver;

use Exception;
use Kdyby\Doctrine\EntityManager;

class DriverService
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
	 * @param Driver $driver
	 *
	 * @throws Exception
	 */
	public function save(Driver $driver): void
	{
		$this->em->persist($driver);
		$this->em->flush($driver);
	}
}
