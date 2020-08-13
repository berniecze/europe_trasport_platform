<?php
declare(strict_types=1);

namespace App\FrontModule\Components\CustomPage;

interface ICustomPageFactory
{

	public function create(callable $onError, ?string $slugUrl): CustomPageControl;
}
