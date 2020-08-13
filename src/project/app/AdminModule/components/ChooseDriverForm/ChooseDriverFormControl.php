<?php
declare(strict_types=1);

namespace App\AdminModule\Components\ChooseDriverForm;

use Exception;
use Nette\Application\AbortException;
use Nette\Application\UI\Control;
use Nette\Application\UI\Form;
use Tomaj\Form\Renderer\BootstrapRenderer;
use Tracy\Debugger;

class ChooseDriverFormControl extends Control
{

	/**
	 * @var Handler
	 */
	private $handler;

	/**
	 * @var callable
	 */
	private $onSuccess;

	/**
	 * @var callable
	 */
	private $onError;

	public function __construct(
		Handler $handler,
		callable $onSuccess,
		callable $onError
	) {
		parent::__construct();
		$this->handler = $handler;
		$this->onSuccess = $onSuccess;
		$this->onError = $onError;
	}

	public function render()
	{
		$template = $this->getTemplate();
		$template->setFile(__DIR__ . '/form.latte');
		$template->render();
	}

	public function createComponentForm(): Form
	{
		$form = new Form();
		$form->setRenderer(new BootstrapRenderer());
		$form->addHidden('order_id');
		$form->addText('surname', 'Driver\'s surname');
		$form->addText('phone', 'Phone number');
		$form->addSubmit('send', 'Assign driver');

		$form->onSuccess[] = function (Form $form) {
			$this->onSuccess($form);
		};
		return $form;
	}

	public function onSuccess(Form $form)
	{
		try {
			$values = $form->getValues(true);
			$orderId = (int)$values['order_id'];
			$this->handler->saveDriverToOrder($orderId, $values['phone'], $values['surname']);
			($this->onSuccess)();
		} catch (Exception $exception) {
			Debugger::log($exception);
			if ($exception instanceof AbortException) {
				throw $exception;
			}
			($this->onError)();
		}
	}
}
