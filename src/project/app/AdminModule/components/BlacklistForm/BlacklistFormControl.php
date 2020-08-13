<?php
declare(strict_types=1);

namespace App\AdminModule\Components\BlacklistForm;

use Exception;
use Nette\Application\AbortException;
use Nette\Application\UI\Control;
use Nette\Application\UI\Form;
use Tomaj\Form\Renderer\BootstrapRenderer;
use Tracy\Debugger;

class BlacklistFormControl extends Control
{

	/**
	 * @var Handler $handler
	 */
	private $handler;

	/**
	 * @var DataProvider $dataProvider
	 */
	private $dataProvider;

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
		DataProvider $dataProvider,
		callable $onSuccess,
		callable $onError
	) {
		parent::__construct();
		$this->handler = $handler;
		$this->dataProvider = $dataProvider;
		$this->onSuccess = $onSuccess;
		$this->onError = $onError;
	}

	public function render(): void
	{
		$template = $this->getTemplate();
		$template->setFile(__DIR__ . '/form.latte');
		$template->render();
	}

	public function createComponentForm(): Form
	{
		$form = new Form();
		$form->setRenderer(new BootstrapRenderer());
		$form->addHidden('id');
		$form->addSelect('transport', 'Transport', $this->dataProvider->getTransportsForSelect());
		$form->addText('from_date', 'From date')->setAttribute('class', 'formDatePicker');
		$form->addText('to_date', 'To date')->setAttribute('class', 'formDatePicker');
		$form->addSubmit('send', 'Save order');

		$form->onSuccess[] = function (Form $form) {
			$this->onSuccess($form);
		};
		return $form;
	}

	private function onSuccess(Form $form): void
	{
		$values = $form->getValues(true);

		try {
			$this->handler->handle($values);
			($this->onSuccess)();
		} catch (Exception $exception) {
			if ($exception instanceof AbortException) {
				throw $exception;
			}
			Debugger::log($exception);
			($this->onError)();
		}
	}

}
