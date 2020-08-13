<?php
declare(strict_types=1);

namespace App\AdminModule\Components\CompanyForm;

use App\Model\Company\Exceptions\CompanyNotFoundException;
use Exception;
use Nette\Application\AbortException;
use Nette\Application\UI\Control;
use Nette\Application\UI\Form;
use Tomaj\Form\Renderer\BootstrapRenderer;
use Tracy\Debugger;

class FormControl extends Control
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
		$form->addHidden('id');

		$form->addText('name', 'Company name')
			->setRequired();
		$form->addText('address', 'Address')
			->setRequired();
		$form->addText('identification_number', 'Identification number (IČO)')
			->setRequired();
		$form->addSubmit('send', 'Save changes');

		$form->onSuccess[] = function (Form $form) {
			$this->onSuccess($form);
		};
		return $form;
	}

	public function onSuccess(Form $form)
	{
		try {
			$values = $form->getValues(true);
			if ($values['id'] === null || $values['id'] === 0) {
				throw new CompanyNotFoundException();
			}

			$request = new CompanyRequest((int) $values['id'], $values['name'], $values['address'], $values['identification_number']);

			$this->handler->saveCompany($request);
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
