<?php
declare(strict_types=1);

namespace App\FrontModule\Presenters;

use App\FrontModule\Components\FavouritesDestinations\FavouritesDestinations;
use App\FrontModule\Components\FavouritesDestinations\IFavouritesDestinationFactory;
use App\FrontModule\Components\FindRouteFormControl\FormControl;
use App\FrontModule\Components\FindRouteFormControl\IFormFactory;
use App\FrontModule\Components\MainPageNavigation\IMainPageNavigationFactory;
use App\FrontModule\Components\MainPageNavigation\MainPageNavigation;
use App\Model\Cart\CartValidityService;
use App\Model\DefaultData\DefaultDataInterface;
use App\Model\DefaultData\EmptyDefaultData;
use App\Model\Destination\DestinationRepository;
use App\Model\Destination\Exceptions\DestinationNotFoundException;
use App\Model\PhotoService;
use Exception;
use Nette\Application\BadRequestException;

class DestinationPresenter extends BasePresenter
{

	/**
	 * @inject
	 * @var IMainPageNavigationFactory $mainPageNavigationFactory
	 */
	public $mainPageNavigationFactory;

	/**
	 * @inject
	 * @var CartValidityService $cartValidityService
	 */
	public $cartValidityService;

	/**
	 * @inject
	 * @var DestinationRepository $destinationRepository
	 */
	public $destinationRepository;

	/**
	 * @inject
	 * @var PhotoService $photoService
	 */
	public $photoService;

	/**
	 * @inject
	 * @var IFormFactory $findRouteFormFactory
	 */
	public $findRouteFormFactory;

	/**
	 * @inject
	 * @var IFavouritesDestinationFactory $favouritesDestinationFactory
	 */
	public $favouritesDestinationFactory;

	/**
	 * @var int $destinationId
	 */
	public $destinationId;

	public function createComponentMainPageNavigation(): MainPageNavigation
	{
		return $this->mainPageNavigationFactory->create();
	}

	public function createComponentFavouritesDestinations(): FavouritesDestinations
	{
		return $this->favouritesDestinationFactory->create($this->destinationId);
	}

	public function createComponentFindRouteForm(): FormControl
	{
		$defaultData = $this->defaultDataService->getPageData($this->destinationId, $this->isCartHashValid());
		$control = $this->findRouteFormFactory->create($defaultData, FormControl::PAGE_MAIN_TYPE);
		$control->onSuccess[] = function (string $cartHash) {
			$this->getHttpResponse()->setCookie(self::USER_COOKIE_CART_HASH, $cartHash, $this->getCookieExpiration());
			$this->redirect('Checkout:transport');
		};
		$control->onError = function () {
			$this->flashMessage('Something went wrong. Please try again later', 'error');
			$this->redrawControl('findRouteForm');
		};
		return $control;
	}

	public function beforeRender()
	{
		parent::beforeRender();
		$name = $this->request->getParameter('slug');

		if ($name === null) {
			$this->flashMessage('Destination does not exist');
			$this->forward('Error:default', new BadRequestException());
		}
		try {
			$destination = $this->destinationRepository->getByName((string)$name);
			$this->destinationId = $destination->getId();
			$this->template->setParameters([
				'destinationName'        => $destination->getName(),
				'destinationPhoto'       => $this->photoService->getDestinationPhoto($destination->getPhoto()),
				'destinationDescription' => $destination->getDescription(),
			]);
		} catch (DestinationNotFoundException $exception) {
			$this->flashMessage('Destination does not exist');
			$this->forward('Error:default', new BadRequestException());
		}
	}
}
