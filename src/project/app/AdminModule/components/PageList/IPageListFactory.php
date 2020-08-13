<?php
declare(strict_types=1);

namespace App\AdminModule\Components\PageList;

interface IPageListFactory
{

	public function create(): PageList;
}
