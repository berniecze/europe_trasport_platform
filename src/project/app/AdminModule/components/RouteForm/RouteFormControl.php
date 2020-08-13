<?php
declare(strict_types=1);

namespace App\AdminModule\Components\RouteForm;

use Nette\Application\AbortException;
use Nette\Application\UI\Control;
use Nette\Application\UI\Form;
use Tomaj\Form\Renderer\BootstrapRenderer;

class RouteFormControl extends Control
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

	public function __construct(Handler $handler, DataProvider $dataProvider, callable $onSuccess, callable $onError)
	{
		parent::__construct();
		$this->handler = $handler;
		$this->dataProvider = $dataProvider;
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
		$form->addSelect('departure', 'Departure', $this->dataProvider->getDestinationsForSelect())
			->setAttribute('class', 'selectize_dropdown');;
		$form->addSelect('arrival', 'Arrival', $this->dataProvider->getDestinationsForSelect())
			->setAttribute('class', 'selectize_dropdown');;
		$form->addCheckbox('active', 'Active');
		$form->addText('price', 'Price €')
			 ->addRule(Form::FLOAT)
			 ->setRequired('Price is a mandatory field');
		$form->addText('distance', 'Distance km')
			 ->addRule(Form::NUMERIC)
			 ->setRequired('Distance is a mandatory field');
		$form->addText('duration', 'Duration of a trip (N hrs N min)')
			 ->setRequired('Route duration is a mandatory field')
			 ->setRequired('Distance is a mandatory field');
		$form->addSubmit('send', 'Save');

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
		} catch (\Exception $exception) {
			if ($exception instanceof AbortException) {
				throw $exception;
			}
			($this->onError)();
		}
	}
}
