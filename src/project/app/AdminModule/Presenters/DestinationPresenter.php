<?php
declare(strict_types=1);

namespace App\AdminModule\Presenters;

use App\AdminModule\Components\DestinationForm\DestinationFormControl;
use App\AdminModule\Components\DestinationForm\DestinationFormFactory;
use App\AdminModule\Components\DestinationList\IListFactory;
use App\AdminModule\Components\DestinationList\ListControl;
use App\Model\Destination\DestinationRepository;
use App\Model\Destination\DestinationService;
use App\Model\Destination\Exceptions\DestinationNotFoundException;
use App\Model\PhotoService;

class DestinationPresenter extends AdminBasePresenter
{

	/**
	 * @var IListFactory
	 */
	private $destinationListFactory;

	/**
	 * @var DestinationFormFactory
	 */
	private $formFactory;

	/**
	 * @var DestinationService
	 */
	private $destinationService;

	/**
	 * @var PhotoService
	 */
	private $photoService;

	/**
	 * @var DestinationRepository
	 */
	private $destinationRepository;

	public function __construct(
		IListFactory $listFactory,
		DestinationFormFactory $formFactory,
		DestinationService $destinationService,
		PhotoService $photoService,
		DestinationRepository $destinationRepository
	) {
		parent::__construct();
		$this->destinationListFactory = $listFactory;
		$this->formFactory = $formFactory;
		$this->destinationService = $destinationService;
		$this->photoService = $photoService;
		$this->destinationRepository = $destinationRepository;
	}

	public function createComponentList(): ListControl
	{
		return $this->destinationListFactory->create();
	}

	public function createComponentDestinationForm(): DestinationFormControl
	{
		$onSuccess = function () {
			$this->flashMessage('Destination saved');
			$this->redirect('Destination:default');
		};
		$onError = function () {
			$this->flashMessage('An error happened. Please try again later.');
			$this->redirect('this');
		};

		return $this->formFactory->create($onSuccess, $onError);
	}

	public function actionEdit($destinationId): void
	{
		if ($destinationId === null) {
			$this->flashMessage('Unknown id four destination administration');
			$this->redirect('default');
		}
		$destination = $this->destinationService->getById((int)$destinationId);

		if ($destination === null) {
			$this->flashMessage('Destination does not exists');
			$this->redirect('default');
		}

		$this->template->name = $destination->getName();
		$this->template->description = $destination->getDescription();
		if ($destination->getPhoto() !== null) {
			$this->template->photo = $this->photoService->getDestinationPhoto($destination->getPhoto());
		}

		if ($destination->getFavourites() !== null) {
			$favouritesIds = explode('|', $destination->getFavourites());
		} else {
			$favouritesIds = [];
		}
		$favourites = [];
		try {
			$favouriteDestinations = $this->destinationRepository->getByIds($favouritesIds);

			foreach ($favouriteDestinations as $favouriteDestination) {
				$favourites[$favouriteDestination->getId()] = $favouriteDestination->getId();
			}
		} catch (DestinationNotFoundException $exception) {
			//do nothing
		}
		$defaultValues = [
			'id'          => $destination->getId(),
			'name'        => $destination->getName(),
			'description' => $destination->getDescription(),
			'active'      => (int)$destination->isActive(),
			'photo'       => $destination->getPhoto(),
			'favourites'  => $favourites,
			'country'     => $destination->getCountry()->getId(),
			'type'        => $destination->getType(),
		];
		$this['destinationForm']['form']->setDefaults($defaultValues);
	}

	public function actionDelete($destinationId): void
	{
		if ($destinationId === null) {
			$this->flashMessage('Unknown id for route administration', 'error');
			$this->redirect('default');
		}
		$destination = $this->destinationService->getById((int)$destinationId);

		if ($destination === null) {
			$this->flashMessage('Destination does not exists', 'error');
			$this->redirect('default');
		}
		try {
			$this->photoService->removeDestinationPhoto($destination->getPhoto());
			$this->destinationService->removeWithRoutes($destination);
		} catch (\Exception $exception) {
			$this->flashMessage('Delete was unsuccessful', 'error');
			$this->redirect('default');
		}
		$this->flashMessage('Destination successfully removed', 'success');
		$this->redirect('default');

	}
}
