<?php
declare(strict_types=1);

namespace App\FrontModule\Components\FavouritesDestinations;

interface IFavouritesDestinationFactory
{
	public function create(int $destinationId): FavouritesDestinations;
}
