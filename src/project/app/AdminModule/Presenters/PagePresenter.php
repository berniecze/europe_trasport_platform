<?php
declare(strict_types=1);

namespace App\AdminModule\Presenters;

use App\AdminModule\Components\PageControl\IPageControlFactory;
use App\AdminModule\Components\PageControl\PageControl;
use App\AdminModule\Components\PageList\IPageListFactory;
use App\AdminModule\Components\PageList\PageList;
use App\Model\Page\Exceptions\PageNotFoundException;
use App\Model\Page\PageRepository;

class PagePresenter extends AdminBasePresenter
{

	/**
	 * @var IPageListFactory
	 */
	private $pageListFactory;

	/**
	 * @var IPageControlFactory
	 */
	private $pageControlFactory;

	/**
	 * @var PageRepository
	 */
	private $pageRepository;

	public function __construct(
		IPageListFactory $pageListFactory,
		IPageControlFactory $pageControlFactory,
		PageRepository $pageRepository
	) {
		parent::__construct();
		$this->pageListFactory = $pageListFactory;
		$this->pageControlFactory = $pageControlFactory;
		$this->pageRepository = $pageRepository;
	}

	public function createComponentPageList(): PageList
	{
		return $this->pageListFactory->create();
	}

	public function createComponentPageForm(): PageControl
	{
		$onSuccess = function () {
			$this->flashMessage('Page saved');
			$this->redirect('Page:default');
		};
		$onError = function () {
			$this->flashMessage('An error happened. Please try again later.');
			$this->redirect('this');
		};

		return $this->pageControlFactory->create($onSuccess, $onError);
	}

	public function actionEdit($pageId): void
	{
		if ($pageId === null) {
			$this->flashMessage('Unknown id four page administration');
			$this->redirect('Page:default');
		}
		$page = null;
		try {
			$page = $this->pageRepository->getById((int)$pageId);
		} catch (PageNotFoundException $exception) {
			$this->flashMessage('Page does not exists');
			$this->redirect('Page:default');
		}

		if ($page === null) {
			$this->flashMessage('Page does not exists');
			$this->redirect('Page:default');
		}

		$defaultValues = [
			'id'                  => $page->getId(),
			'template'            => $page->getTemplate(),
			'name'                => $page->getName(),
			'url'                 => $page->getUrl(),
			'active'              => $page->isActive(),
			'content'             => $page->getContent(),
			'seo_description'     => $page->getSeoDescription(),
			'seo_keywords'        => $page->getSeoKeywords(),
			'title'               => $page->getTitle(),
			'show_search_form'    => $page->isShowSearchForm(),
			'search_default_from' => $page->getSearchDefaultFrom()->getId(),
			'search_default_to'   => $page->getSearchDefaultTo()->getId(),
		];

		$this['pageForm']['form']->setDefaults($defaultValues);
	}
}
