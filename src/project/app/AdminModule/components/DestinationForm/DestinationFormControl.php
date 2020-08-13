<?php
declare(strict_types=1);

namespace App\AdminModule\Components\DestinationForm;

use App\Model\Destination\Destination;
use App\Model\PhotoService;
use Exception;
use Nette\Application\AbortException;
use Nette\Application\UI\Control;
use Nette\Application\UI\Form;
use Nette\Http\FileUpload;
use Tomaj\Form\Renderer\BootstrapRenderer;

class DestinationFormControl extends Control
{

	/**
	 * @var Handler $handler
	 */
	private $handler;

	/**
	 * @var PhotoService $photoService
	 */
	private $photoService;

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
		PhotoService $photoService,
		DataProvider $dataProvider,
		callable $onSuccess,
		callable $onError
	) {
		parent::__construct();
		$this->handler = $handler;
		$this->photoService = $photoService;
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
		$form->addText('name', 'Name')
			 ->setRequired('Destination name is required parameter');
		$form->addTextArea('description', 'Description')
			 ->setRequired('Destination description is required parameter');
		$form->addUpload('photo', 'Cover image');
		$form->addSelect('country', 'Country', $this->dataProvider->getCountryListForSelect());
		$form->addSelect('type', 'Type of destination', $this->getTypesForSelect());
		$form->addCheckbox('active', 'Active');
		$form->addMultiSelect('favourites', 'Favourites destination', $this->dataProvider->getDestinations());
		$form->addSubmit('send', 'Save');

		$form->onSuccess[] = function (Form $form) {
			$this->onSuccess($form);
		};
		return $form;
	}

	private function onSuccess(Form $form): void
	{
		$values = $form->getValues(true);
		$photo = $values['photo'];

		try {
			/** @var FileUpload $photo */
			if ($photo->getTemporaryFile() !== null) {
				$photo = $this->photoService->saveDestinationPhoto($photo);
			}
			$this->handler->handle($values, $photo->getName());

			($this->onSuccess)();
		} catch (Exception $exception) {
			if ($exception instanceof AbortException) {
				throw $exception;
			}
			($this->onError)();
		}
	}

	private function getTypesForSelect(): array
	{
		return [
			0                              => 'none',
			Destination::TYPE_CITY_TEXT    => Destination::TYPE_CITY_TEXT,
			Destination::TYPE_AIRPORT_TEXT => Destination::TYPE_AIRPORT_TEXT,
		];
	}
}
