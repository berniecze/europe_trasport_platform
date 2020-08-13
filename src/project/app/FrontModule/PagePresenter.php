<?php
declare(strict_types=1);

namespace App\FrontModule\Presenters;

use App\FrontModule\Components\CustomPage\ICustomPageFactory;
use App\FrontModule\Components\FindRouteFormControl\FormControl;
use App\FrontModule\Components\FindRouteFormControl\IFormFactory;
use App\FrontModule\Components\MainPageNavigation\IMainPageNavigationFactory;
use App\FrontModule\Components\MainPageNavigation\MainPageNavigation;
use App\Model\DefaultData\PageDefaultData;
use App\Model\Page\Exceptions\PageNotFoundException;
use App\Model\Page\PageRepository;
use Nette\Application\BadRequestException;
use Tracy\Debugger;

final class PagePresenter extends BasePresenter
{

	/**
	 * @var PageRepository $pageRepository
	 */
	private $pageRepository;

	/**
	 * @var ICustomPageFactory $customPageFactory
	 */
	private $customPageFactory;

	/**
	 * @var string $urlSlug
	 */
	private $urlSlug;

	/**
	 * @var IFormFactory $findRouteFormFactory
	 */
	private $findRouteFormFactory;

	/**
	 * @inject
	 * @var IMainPageNavigationFactory $mainPageNavigationFactory
	 */
	public $mainPageNavigationFactory;

	/**
	 * @var PageDefaultData|null $pageDefaultFormData
	 */
	private $pageDefaultFormData;

	public function __construct(
		PageRepository $pageRepository,
		ICustomPageFactory $customPageFactory,
		IFormFactory $findRouteFormFactory
	) {
		parent::__construct();
		$this->pageRepository = $pageRepository;
		$this->customPageFactory = $customPageFactory;
		$this->findRouteFormFactory = $findRouteFormFactory;
	}

	public function renderDefault()
	{
		$slug = $this->request->getParameter('slug');

		if ($slug === null) {
			$this->flashMessage('Page does not exist');
			$this->forward('Error:default', new BadRequestException());
		}
		try {
			$this->urlSlug = $slug;
			$page = $this->pageRepository->getByUrl($this->urlSlug);

			if (!$page->isActive()) {
				$this->flashMessage('There has been some technical issue, please try later ');
				$this->forward('Error:default', new BadRequestException());
			}

			$templateFile = sprintf('%s/templates/Page/%s.latte', __DIR__, $page->getTemplate());
			if (!file_exists($templateFile)) {
				Debugger::log('Template for custom page does not exist', 'error');
				$this->flashMessage('There has been some technical issue, please try later ');
				$this->forward('Error:default', new BadRequestException());
			}

			$templateData = [
				'title'          => $page->getTitle(),
				'name'           => $page->getName(),
				'content'        => $page->getContent(),
				'seoKeywords'    => $page->getSeoKeywords(),
				'seoDescription' => $page->getSeoDescription(),
			];

			$fromDestination = $page->getSearchDefaultFrom();
			$toDestination = $page->getSearchDefaultTo();
			$this->pageDefaultFormData = new PageDefaultData($fromDestination ? $fromDestination->getId() : null,
				$fromDestination ? $toDestination->getId() : null);

			$this->template->setFile($templateFile);
			$this->template->setParameters($templateData);
		} catch (PageNotFoundException $exception) {
			$this->flashMessage('Page does not exist');
			$this->forward('Error:default', new BadRequestException());
		}
	}

	public function createComponentCustomPage()
	{
		$onError = function () {
			$this->flashMessage('Page does not exist');
			$this->forward('Error:default', new BadRequestException());
		};
		return $this->customPageFactory->create($onError, $this->urlSlug);
	}

	public function createComponentMainPageNavigation(): MainPageNavigation
	{
		return $this->mainPageNavigationFactory->create(MainPageNavigation::PAGE_SUB_PAGE_TYPE);
	}

	public function createComponentFindRouteForm(): FormControl
	{

		$control = $this->findRouteFormFactory->create($this->pageDefaultFormData, FormControl::CUSTOM_PAGE_TYPE);
		$control->onSuccess[] = function (?string $cartHash) {
			$this->getHttpResponse()->setCookie(self::USER_COOKIE_CART_HASH, $cartHash, $this->getCookieExpiration());
			$this->redirect('Checkout:transport');
		};
		$control->onError = function () {
			$this->flashMessage('Something went wrong. Please try again later', 'error');
			$this->redrawControl('findRouteForm');
		};
		return $control;
	}
}
