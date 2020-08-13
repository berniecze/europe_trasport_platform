<?php
declare(strict_types=1);

namespace App\Router;

use App\Model\Destination\DestinationRepository;
use App\Model\Page\PageRepository;
use Nette;
use Nette\Application\Routers\Route;
use Nette\Application\Routers\RouteList;

final class RouterFactory
{

	/**
	 * @var DestinationRepository
	 */
	private $destinationRepository;

	/**
	 * @var PageRepository
	 */
	private $pageRepository;

	public function __construct(DestinationRepository $destinationRepository, PageRepository $pageRepository)
	{
		$this->destinationRepository = $destinationRepository;
		$this->pageRepository = $pageRepository;
	}

	public function createRouter(): Nette\Application\IRouter
	{
		$router = new RouteList();

		$router[] = new Route('', 'Front:Homepage:default');

		$admin = new RouteList('Admin');
		$admin[] = new Route('admin/<presenter>/<action>[/<id>]', 'Homepage:default');
		$router[] = $admin;

		$public = new RouteList('Front');

		$public[] = new Route('city/<slug>', array(
			'presenter' => 'Destination',
			'action'    => 'default',
			'post'      => array(
				Route::FILTER_IN  => function ($name) {
					return $this->destinationRepository->getByName($name);
				},
				Route::FILTER_OUT => function ($post) {
					return $post->slug;
				}
			),
		));
		$public[] = new Route('transfer/<slug>', array(
			'presenter' => 'Page',
			'action'    => 'default',
			'post'      => array(
				Route::FILTER_IN  => function ($name) {
					return $this->pageRepository->getByUrl($name);
				},
				Route::FILTER_OUT => function ($post) {
					return $post->slug;
				}
			),
		));
		$public[] = new Route('<presenter>/<action>[/<id>]', 'Front:Homepage:default');

		$router[] = $public;

		return $router;
	}
}
