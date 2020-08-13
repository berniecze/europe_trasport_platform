<?php
declare(strict_types=1);

namespace App\Model\Page;

use Exception;
use Kdyby\Doctrine\EntityManager;

class PageService
{

	/**
	 * @var EntityManager $em
	 */
	private $em;

	public function __construct(EntityManager $entityManager)
	{
		$this->em = $entityManager;
	}

	/**
	 * @param Page $page
	 *
	 * @throws Exception
	 */
	public function save(Page $page): void
	{
		$this->em->persist($page);
		$this->em->flush($page);
	}

	/**
	 * @param Page $page
	 *
	 * @throws Exception
	 */
	public function remove(Page $page): void
	{
		$this->em->remove($page);
		$this->em->flush($page);
	}
}
