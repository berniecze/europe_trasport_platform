<?php
declare(strict_types=1);

namespace App\AdminModule\Components\PageList;

use App\Model\Page\Exceptions\PageNotFoundException;
use App\Model\Page\PageRepository;
use Kdyby\Doctrine\ResultSet;

class DataProvider
{

	/**
	 * @var PageRepository
	 */
	private $pageRepository;

	public function __construct(PageRepository $pageRepository)
	{
		$this->pageRepository = $pageRepository;
	}

	/**
	 * @return ResultSet
	 * @throws PageNotFoundException
	 */
	public function getAllPages(): ResultSet
	{
		return $this->pageRepository->getAll();
	}
}
