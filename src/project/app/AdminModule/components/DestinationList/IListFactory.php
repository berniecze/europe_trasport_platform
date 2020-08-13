<?php
declare(strict_types=1);

namespace App\AdminModule\Components\DestinationList;

interface IListFactory
{

	public function create(): ListControl;
}
