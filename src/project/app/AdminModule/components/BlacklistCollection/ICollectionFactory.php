<?php
declare(strict_types=1);

namespace App\AdminModule\Components\BlacklistCollection;

interface ICollectionFactory
{
	public function create(?int $selectedTransport = null): BlacklistCollection;
}
