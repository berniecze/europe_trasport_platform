<?php
declare(strict_types=1);

namespace App\AdminModule\Components\ClientOrderForm;

use App\Model\ClientOrder\ClientOrder;
use Exception;
use Nette\Application\AbortException;
use Nette\Application\UI\Control;
use Nette\Application\UI\Form;
use Tomaj\Form\Renderer\BootstrapRenderer;

class ClientOrderFormControl extends Control
{

	/**
	 * @var Handler $handler
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

	public function __construct(Handler $handler, callable $onSuccess, callable $onError)
	{
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
		$form->addSelect('status', 'Order status', $this->getStatusesForSelect());
		$form->addText('final_price', 'Paid online €');
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
			($this->onError)();
		}
	}

	private function getStatusesForSelect(): array
	{
		return [
			ClientOrder::ACCEPTED_ORDERS     => 'Accepted',
			ClientOrder::NOT_ACCEPTED_ORDERS => 'Not Accepted',
			ClientOrder::FINISHED_ORDERS     => 'Finished',
			ClientOrder::CANCELLED_ORDERS    => 'Cancelled',
		];
	}
}
