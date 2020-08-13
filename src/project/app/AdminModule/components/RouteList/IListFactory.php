<?php
declare(strict_types=1);

namespace App\AdminModule\Components\RouteList;

interface IListFactory
{

	public function create(): ListControl;
}
