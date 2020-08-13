<?php
declare(strict_types=1);

namespace App\AdminModule\Presenters;

use App\AdminModule\Components\LoginForm\FormControl;
use App\AdminModule\Components\LoginForm\IFormFactory;

class SignPresenter extends AdminBasePresenter
{

	/**
	 * @var IFormFactory
	 */
	private $loginFormFactory;

	public function __construct(IFormFactory $loginFormFactory)
	{
		parent::__construct();
		$this->loginFormFactory = $loginFormFactory;
	}

	public function actionIn()
	{
		// If is user logged in, redirect him
		if ($this->getUser()->isLoggedIn()) {
			$this->flashMessage('You are already signed');
			$this->redirect('Homepage:default');
		}
	}

	public function actionOut()
	{
		$this->user->logout();
		$this->flashMessage('You have been signed off');
		$this->redirect('in');
	}

	public function createComponentLoginForm(): FormControl
	{
		$onSuccess = function () {
			$this->flashMessage('You have been logged in');
			$this->redirect('Homepage:default');
		};
		$onError = function () {
			$this->flashMessage('Your credentials are invalid');
			$this->redirect('in');
		};

		return $this->loginFormFactory->create($onSuccess, $onError);
	}

}
