<?php
declare(strict_types=1);

namespace App\AdminModule\Presenters;

use App\AdminModule\Components\BlacklistCollection\BlacklistCollection;
use App\AdminModule\Components\BlacklistCollection\ICollectionFactory;
use App\AdminModule\Components\BlacklistForm\BlacklistFormControl;
use App\AdminModule\Components\BlacklistForm\IBlacklistFormFactory;
use Application\Blacklist\Request\GetBlacklistRequest;
use Application\Blacklist\Request\RemoveBlacklistRequest;
use Application\Blacklist\UseCase\GetBlacklistUseCase;
use Application\Blacklist\UseCase\RemoveBlacklistUseCase;
use Exception;
use Infrastructure\Exception\BlacklistNotFoundException;
use Tracy\Debugger;

class BlacklistPresenter extends AdminBasePresenter
{

	/**
	 * @var ICollectionFactory
	 */
	private $collectionFactory;

	/**
	 * @var IBlacklistFormFactory
	 */
	private $blacklistFormFactory;

	/**
	 * @var GetBlacklistUseCase
	 */
	private $getBlacklistUseCase;

	/**
	 * @var RemoveBlacklistUseCase
	 */
	private $removeBlacklistUseCase;

	public function __construct(
		ICollectionFactory $collectionFactory,
		IBlacklistFormFactory $blacklistFormFactory,
        GetBlacklistUseCase $getBlacklistUseCase,
        RemoveBlacklistUseCase $removeBlacklistUseCase
	) {
		parent::__construct();
		$this->collectionFactory = $collectionFactory;
		$this->blacklistFormFactory = $blacklistFormFactory;
		$this->getBlacklistUseCase = $getBlacklistUseCase;
		$this->removeBlacklistUseCase = $removeBlacklistUseCase;
	}

	public function createComponentList(): BlacklistCollection
	{
		return $this->collectionFactory->create();
	}

	public function createComponentBlacklistForm(): BlacklistFormControl
	{
		$onSuccess = function () {
			$this->flashMessage('Blacklist for the car has been saved');
			$this->redirect('Blacklist:default');
		};
		$onError = function () {
			$this->flashMessage('An error happened. Please try again later.');
			$this->redirect('this');
		};

		return $this->blacklistFormFactory->create($onSuccess, $onError);
	}

	public function actionDelete($blacklistId): void
	{
		if ($blacklistId === null) {
			$this->flashMessage('Unknown id for Blacklist administration');
			$this->redirect('Blacklist:default');
		}

		try {
		    $request = new RemoveBlacklistRequest((int) $blacklistId);

		    $this->removeBlacklistUseCase->execute($request);

            $this->flashMessage('Blacklist successfully removed', 'success');
            $this->redirect('default');
        } catch (BlacklistNotFoundException $exception) {
            $this->flashMessage('Blacklist does not exists');
            $this->redirect('Blacklist:default');
        } catch (BlacklistNotFoundException $exception) {
            Debugger::log($exception);
            $this->flashMessage('An error happened. Please try again later.');
            $this->redirect('this');
        }
	}

	public function actionEdit($blacklistId): void
	{
		if ($blacklistId === null) {
			$this->flashMessage('Unknown id for blacklist administration');
			$this->redirect('Blacklist:default');
		}
		try {
            $blacklist = $this->getBlacklistUseCase->execute(new GetBlacklistRequest((int) $blacklistId));

            $defaultValues = [
                'id'        => $blacklist->getId(),
                'transport' => $blacklist->getTransport()->getId(),
                'from_date' => $blacklist->getFromDate()->getValue()->format('Y-m-d H:i:s'),
                'to_date'   => $blacklist->getToDate()->getValue()->format('Y-m-d H:i:s'),
            ];
            $this['blacklistForm']['form']->setDefaults($defaultValues);
        } catch (BlacklistNotFoundException $exception) {
            $this->flashMessage('Blacklist does not exists');
            $this->redirect('Blacklist:default');
        } catch (Exception $exception) {
		    Debugger::log($exception);
            $this->flashMessage('An error happened. Please try again later.');
            $this->redirect('this');
        }
	}
}
