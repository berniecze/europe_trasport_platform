<?php
declare(strict_types=1);

namespace App\AdminModule\Components\TransportList;

interface IListFactory
{

	public function create(): TransportList;
}
