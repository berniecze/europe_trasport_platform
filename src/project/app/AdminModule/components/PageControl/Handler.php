<?php
declare(strict_types=1);

namespace App\AdminModule\Components\PageControl;

use App\Model\Destination\DestinationRepository;
use App\Model\Page\Exceptions\PageNotFoundException;
use App\Model\Page\Page;
use App\Model\Page\PageRepository;
use App\Model\Page\PageService;

class Handler
{

	/**
	 * @var PageRepository
	 */
	private $pageRepository;

	/**
	 * @var PageService
	 */
	private $pageService;

	/**
	 * @var DestinationRepository
	 */
	private $destinationRepository;

	public function __construct(
		PageRepository $pageRepository,
		PageService $pageService,
		DestinationRepository $destinationRepository
	) {
		$this->pageRepository = $pageRepository;
		$this->pageService = $pageService;
		$this->destinationRepository = $destinationRepository;
	}

	/**
	 * @param array $values
	 *
	 * @throws PageNotFoundException|\Exception
	 */
	public function handle(array $values)
	{
		if (!$values['id']) {
			$page = new Page();
		} else {
			$page = $this->pageRepository->getById((int)$values['id']);
		}
		if ($page === null) {
			throw PageNotFoundException::byPrimaryKey($values['id']);
		}

		$departure = $this->destinationRepository->getById($values['search_default_from']);
		$arrival = $this->destinationRepository->getById($values['search_default_to']);

		$page->setActive($values['active']);
		$page->setContent($values['content']);
		$page->setName($values['name']);
		$page->setUrl($values['url']);
		$page->setSeoDescription($values['seo_description']);
		$page->setSeoKeywords($values['seo_keywords']);
		$page->setTitle($values['title']);
		$page->setTemplate($values['template']);
		$page->setShowSearchForm($values['show_search_form']);
		$page->setSearchDefaultFrom($departure);
		$page->setSearchDefaultTo($arrival);
		$this->pageService->save($page);
	}
}
