<?php
declare(strict_types=1);

namespace App\AdminModule\Components\MainPageNavigation;

interface IMainNavigationFactory
{

	public function create(): MainPageNavigation;
}
