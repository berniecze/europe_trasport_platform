<?php
declare(strict_types=1);

namespace App\AdminModule\Presenters;

use Exception;
use App\AdminModule\Components\BlacklistCollection\BlacklistCollection;
use App\AdminModule\Components\BlacklistCollection\ICollectionFactory;
use App\AdminModule\Components\TransportForm\IFormFactory;
use App\AdminModule\Components\TransportForm\TransportFormControl;
use App\AdminModule\Components\TransportList\IListFactory;
use App\AdminModule\Components\TransportList\TransportList;
use App\Model\PhotoService;
use Application\Transport\Request\GetTransportRequest;
use Application\Transport\Request\RemoveTransportRequest;
use Application\Transport\UseCase\GetTransportUseCase;
use Application\Transport\UseCase\RemoveTransportUseCase;
use Infrastructure\Exception\TransportNotFoundException;
use Nette\Application\AbortException;

class TransportPresenter extends AdminBasePresenter
{

	/**
	 * @var IListFactory
	 */
	private $transportListFactory;

	/**
	 * @var IFormFactory
	 */
	private $formFactory;

	/**
	 * @var PhotoService
	 */
	private $photoService;

	/**
	 * @var ICollectionFactory
	 */
	private $collectionFactory;

	/**
	 * @var int
	 */
	private $transportId;

    /**
     * @var GetTransportUseCase
     */
	private $getTransportUseCase;

    /**
     * @var RemoveTransportUseCase
     */
	private $removeTransportUseCase;

	public function __construct(
		IListFactory $listFactory,
		IFormFactory $formFactory,
		PhotoService $photoService,
		ICollectionFactory $collectionFactory,
        GetTransportUseCase $getTransportUseCase,
        RemoveTransportUseCase $removeTransportUseCase
	) {
		parent::__construct();
		$this->transportListFactory = $listFactory;
		$this->formFactory = $formFactory;
		$this->photoService = $photoService;
		$this->collectionFactory = $collectionFactory;
		$this->getTransportUseCase = $getTransportUseCase;
		$this->removeTransportUseCase = $removeTransportUseCase;
	}

	public function createComponentList(): TransportList
	{
		return $this->transportListFactory->create();
	}

	public function createComponentBlacklistCollection(): BlacklistCollection
	{
		return $this->collectionFactory->create($this->transportId);
	}

	public function createComponentTransportForm(): TransportFormControl
	{
		$onSuccess = function () {
			$this->flashMessage('Transport saved');
			$this->redirect('Transport:default');
		};
		$onError = function () {
			$this->flashMessage('An error happened. Please try again later.');
			$this->redirect('this');
		};

		return $this->formFactory->create($onSuccess, $onError);
	}

	public function actionEdit($transportId): void
	{
		if ($transportId === null) {
			$this->flashMessage('Unknown id for transport administration');
			$this->redirect('default');
		}
		try {
            $getTransportRequest = new GetTransportRequest((int) $transportId);
            $transport = $this->getTransportUseCase->execute($getTransportRequest);

            $photoUrl = $transport->getPhotoUrl() ? $transport->getPhotoUrl()->getValue() : null;

			$defaultValues = [
				'id'              => $transport->getId(),
				'name'            => $transport->getName()->getValue(),
				'description'     => $transport->getDescription()->getValue(),
				'luggage'         => $transport->getLuggage()->getValue(),
				'capacity'        => $transport->getCapacity()->getValue(),
				'fixedPrice'      => $transport->getFixedPrice()->getValue(),
				'multiplierPrice' => $transport->getMultiplierPrice()->getValue(),
				'active'          => (int) $transport->getActive()->getValue(),
				'photo'           => $photoUrl,
			];

			$this->template->setParameters($defaultValues);
			$this->template->photo = $this->photoService->getTransportPhoto($photoUrl);
			$this['transportForm']['form']->setDefaults($defaultValues);
		} catch (Exception $exception) {
			$this->flashMessage('Transport does not exists');
			$this->redirect('default');
		}
	}

	public function actionDelete($transportId): void
	{
		if ($transportId === null) {
			$this->flashMessage('Transport does not exists', 'error');
			$this->redirect('default');
		}

		try {
		    $getTransportRequest = new GetTransportRequest((int) $transportId);
			$transport = $this->getTransportUseCase->execute($getTransportRequest);

			$removeTransportRequest = new RemoveTransportRequest($transport->getId(), $transport->getPhotoUrl());
			$this->removeTransportUseCase->execute($removeTransportRequest);

            $this->flashMessage('Transport successfully removed', 'success');
            $this->redirect('default');
		} catch (TransportNotFoundException $exception) {
			$this->flashMessage('Transport does not exists');
			$this->redirect('default');
		} catch (Exception $exception) {
            if ($exception instanceof AbortException) {
                throw $exception;
            }
		    \Tracy\Debugger::log($exception);
			$this->flashMessage('Error happened during removing the transport. Try again later');
			$this->redirect('default');
		}
	}

	public function actionBlacklist($transportId): void
	{
		if ($transportId === null) {
			$this->flashMessage('Transport does not exists', 'error');
			$this->redirect('default');
		}
		try {
            $getTransportRequest = new GetTransportRequest((int) $transportId);
            $transport = $this->getTransportUseCase->execute($getTransportRequest);

			$this->transportId = $transport->getId();
			$this->template->name = $transport->getName()->getValue();
		} catch (Exception $exception) {
			$this->flashMessage('Transport does not exists');
			$this->redirect('default');
		}
	}
}
