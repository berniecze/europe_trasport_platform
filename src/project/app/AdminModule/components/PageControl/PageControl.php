<?php
declare(strict_types=1);

namespace App\AdminModule\Components\PageControl;

use App\Model\Page\Page;
use Exception;
use Nette\Application\AbortException;
use Nette\Application\UI\Control;
use Nette\Application\UI\Form;
use Tomaj\Form\Renderer\BootstrapRenderer;

class PageControl extends Control
{

	/**
	 * @var Handler
	 */
	private $handler;

	/**
	 * @var DataProvider
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

	public function createComponentForm(): Form
	{
		$form = new Form();
		$data = $this->dataProvider->getDestinationsForSelect();

		$form->setRenderer(new BootstrapRenderer());
		$form->addHidden('id');
		$form->addSelect('template', 'Page template', [
				Page::TEMPLATE_WITHOUT_FORM => 'Centered content',
				Page::TEMPLATE_WITH_FORM    => 'Centered with form content',
			]
		)->setRequired('You need to select template for your page');
		$form->addText('title', 'Page title');
		$form->addText('url', 'Page url')
			 ->setRequired(true);
		$form->addCheckbox('active', 'Visible');
		$form->addTextArea('content', 'Page content');
		$form->addText('name', 'Page name')
			 ->setRequired(true);
		$form->addText('seo_description', 'Page description for SEO');
		$form->addText('seo_keywords', 'Page keywords for SEO');
		$form->addCheckbox('show_search_form', 'Show search form');
		$form->addSelect('search_default_from', 'From', $data);
		$form->addSelect('search_default_to', 'To', $data);
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

	public function render()
	{
		$this->template->setFile(__DIR__ . '/form.latte');
		$this->template->render();
	}
}
