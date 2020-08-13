<?php
declare(strict_types=1);

namespace App\FrontModule\Components\MainPageNavigation;

use Nette\Application\UI\Control;

class MainPageNavigation extends Control
{
	public const PAGE_MAIN_TYPE     = 'mainpage';
	public const PAGE_SUB_PAGE_TYPE = 'subpage';

	/**
	 * @var string
	 */
	private $pageType;

	public function __construct(string $pageType = MainPageNavigation::PAGE_MAIN_TYPE)
	{
		parent::__construct();
		$this->pageType = $pageType;

	}

	public function render()
	{
		$template = $this->getTemplate();
		$template->setFile(__DIR__ . '/subpage.latte');
		if ($this->pageType === self::PAGE_MAIN_TYPE) {
			$template->setFile(__DIR__ . '/main.latte');
		}
		$template->render();
	}
}
