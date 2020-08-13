<?php
declare(strict_types=1);

namespace App\FrontModule\Components\CustomPage;

use App\Model\Page\Exceptions\PageNotFoundException;
use App\Model\Page\Page;
use Nette\Application\UI\Control;

class CustomPageControl extends Control
{

	/**
	 * @var string
	 */
	private $slugUrl;

	/**
	 * @var callable
	 */
	private $onError;

	/**
	 * @var DataProvider
	 */
	private $dataProvider;

	public function __construct(callable $onError, ?string $slugUrl, DataProvider $dataProvider)
	{
		parent::__construct();
		$this->onError = $onError;
		$this->slugUrl = $slugUrl;
		$this->dataProvider = $dataProvider;
	}

	public function render(): void
	{
		try {
			$page = $this->dataProvider->getPageBySlug($this->slugUrl);

			if (!$this->isValidTemplate($page)) {
				($this->onError)();
			}
			\Tracy\Debugger::barDump($page->getTemplate());
			$this->template->setFile(sprintf('%s/%s.latte', __DIR__, $page->getTemplate()));
			$this->template->content = $page->getContent();
		} catch (PageNotFoundException $exception) {
			($this->onError)();
		}
	}

	private function isValidTemplate(Page $page): bool
	{
		$validTemplates = [Page::TEMPLATE_ONE_COLUMN];
		return in_array($page->getTemplate(), $validTemplates);
	}
}
