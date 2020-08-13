<?php
declare(strict_types=1);

namespace App\AdminModule\Presenters;

use App\AdminModule\Components\MainPageNavigation\IMainNavigationFactory;
use App\AdminModule\Components\MainPageNavigation\MainPageNavigation;
use Nette;

class AdminBasePresenter extends Nette\Application\UI\Presenter
{

	/**
	 * @var IMainNavigationFactory
	 * @inject
	 */
	public $mainNavigationFactory;

	protected function startup()
	{
		parent::startup();

		if (!$this->getUser()->isLoggedIn() && $this->getRequest()->getPresenterName() !== 'Admin:Sign') {
			if ($this->getUser()->logoutReason === Nette\Security\IUserStorage::INACTIVITY) {
				$this->flashMessage('You have been log out due to inactivity');
			}

			$this->redirect(':Admin:Sign:in');
		}
	}

	public function createComponentMainNavigation(): MainPageNavigation
	{
		return $this->mainNavigationFactory->create();
	}
}
