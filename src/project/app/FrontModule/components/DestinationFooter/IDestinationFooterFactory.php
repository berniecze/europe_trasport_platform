<?php
declare(strict_types=1);

namespace App\FrontModule\Components\DestinationFooter;

interface IDestinationFooterFactory
{
	public function create(): DestinationFooter;
}
