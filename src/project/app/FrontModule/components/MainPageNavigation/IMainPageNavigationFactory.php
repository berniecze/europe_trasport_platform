<?php
declare(strict_types=1);

namespace App\FrontModule\Components\MainPageNavigation;

interface IMainPageNavigationFactory
{

	public function create(string $pageType = MainPageNavigation::PAGE_MAIN_TYPE): MainPageNavigation;
}
