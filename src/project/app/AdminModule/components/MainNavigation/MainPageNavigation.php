<?php
declare(strict_types=1);

namespace App\AdminModule\Components\MainPageNavigation;

use Nette\Application\UI\Control;
use Nette\Security\User;

class MainPageNavigation extends Control
{
	/**
	 * @var User
	 */
	private $user;

	public function __construct(User $user)
	{
		parent::__construct();
		$this->user = $user;
	}

	public function render()
	{
		$this->template->setFile(__DIR__ . '/default.latte');
		$this->template->isUserlogged = $this->user->isLoggedIn();
		$this->template->render();
	}
}
