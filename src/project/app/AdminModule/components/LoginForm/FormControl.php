<?php
declare(strict_types=1);

namespace App\AdminModule\Components\LoginForm;

use Nette\Application\UI\Control;
use Nette\Application\UI\Form;
use Nette\Security\AuthenticationException;
use Nette\Security\User;
use Tomaj\Form\Renderer\BootstrapRenderer;

class FormControl extends Control
{

	/**
	 * @var callable
	 */
	private $onSuccess;

	/**
	 * @var callable
	 */
	private $onError;

	/**
	 * @var User
	 */
	private $user;

	public function __construct(callable $onSuccess, callable $onError, User $user)
	{
		parent::__construct();
		$this->onSuccess = $onSuccess;
		$this->onError = $onError;
		$this->user = $user;
	}

	public function render()
	{
		$template = $this->getTemplate();
		$template->setFile(__DIR__ . '/form.latte');
		$template->render();
	}

	public function createComponentForm(): Form
	{
		$form = new Form;
		$form->setRenderer(new BootstrapRenderer());
		$form->addText('username', 'Username')
			 ->setAttribute('placeholder', 'Your username')
			 ->setRequired('Username is required field');

		$form->addPassword('password', 'Password')
			 ->setAttribute('placeholder', 'Your password to admin')
			 ->setRequired('Password is required field');

		$form->addSubmit('send', 'Log in');

		$form->onSuccess[] = function ($form) {
			$this->signInFormSucceeded($form);
		};

		return $form;
	}

	public function signInFormSucceeded(Form $form)
	{
		$values = $form->getValues();
		try {
			$this->user->login(
				$values->username,
				$values->password
			);
			$this->user->setExpiration('3 days', false);

			($this->onSuccess)();
		} catch (AuthenticationException $e) {
			($this->onError)();
		}
	}
}
