<?php
declare(strict_types=1);

namespace App\FrontModule\Components\CustomPage;

use App\Model\Page\Exceptions\PageNotFoundException;
use App\Model\Page\Page;
use App\Model\Page\PageRepository;

class DataProvider
{

	/**
	 * @var PageRepository $pageRepository
	 */
	private $pageRepository;

	public function __construct(PageRepository $pageRepository)
	{
		$this->pageRepository = $pageRepository;
	}

	/**
	 * @param string $urlSlug
	 *
	 * @return Page
	 * @throws PageNotFoundException
	 */
	public function getPageBySlug(string $urlSlug): Page
	{
		return $this->pageRepository->getByUrl($urlSlug);
	}
}
