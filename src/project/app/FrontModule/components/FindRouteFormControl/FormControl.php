<?php
declare(strict_types=1);

namespace App\FrontModule\Components\FindRouteFormControl;

use App\Model\DateTimeService;
use App\Model\DefaultData\DefaultDataInterface;
use App\Model\Destination\Exceptions\DestinationNotFoundException;
use App\Model\Route\Exceptions\RouteNotFoundException;
use DateTime;
use Nette\Application\AbortException;
use Nette\Application\UI\Control;
use Nette\Application\UI\Form;
use Tracy\Debugger;

/**
 * @method onSuccess(string $cartHash)
 * @method onError()
 */
class FormControl extends Control
{
	public const  PAGE_MAIN_TYPE        = 'main_page';
	public const  PAGE_TRANSPORT_CHOOSE = 'transport_choose';
	public const  CUSTOM_PAGE_TYPE      = 'custom_page';
	private const DATE_INPUT_FORMAT = 'Y-m-d';
	private const TIME_INPUT_FORMAT = 'H:i';

	/**
	 * @var callable[]
	 */
	public $onSuccess = [];

	/**
	 * @var callable[]
	 */
	public $onError = [];

	/**
	 * @var Handler
	 */
	private $handler;

	/**
	 * @var DateTimeService
	 */
	private $dateTimeService;

	/**
	 * @var DataProvider
	 */
	private $dataProvider;

	/**
	 * @var string
	 */
	private $pageType;

	/**
	 * @var DefaultDataInterface
	 */
	private $customerDefaultData;

	public function __construct(
		Handler $handler,
		DateTimeService $dateTimeService,
		DataProvider $dataProvider,
		?DefaultDataInterface $customerDefaultData = null,
		string $pageType = self::PAGE_MAIN_TYPE
	) {
		parent::__construct();
		$this->handler = $handler;
		$this->dateTimeService = $dateTimeService;
		$this->dataProvider = $dataProvider;
		$this->pageType = $pageType;
		$this->customerDefaultData = $customerDefaultData;
	}

	public function render(): void
	{
		$template = $this->getTemplate();

		if ($this->pageType === self::PAGE_MAIN_TYPE) {
			$template->setFile(__DIR__ . '/form.latte');
		} else {
			if ($this->pageType === self::CUSTOM_PAGE_TYPE) {
				$template->setFile(__DIR__ . '/customPage.latte');
			} else {
				$template->setFile(__DIR__ . '/transportChoose.latte');
			}
		}

		$this->template->destinationsData = $this->dataProvider->getDestinationsForSelectTemplate();
		$this->template->defaultFrom = null;
		$this->template->defaultTo = null;

		$this->template->defaultFrom = $this->customerDefaultData->getFromId();
		$this->template->defaultTo = $this->customerDefaultData->getToId();
		$template->render();
	}

	public function createComponentForm(): Form
	{
		$form = new Form();
        $data = $this->dataProvider->getDestinationsForSelect();
        $maximalCapacity = $this->dataProvider->getMaximalCapacity()->getValue();

        $form->getElementPrototype()->addAttributes(['class' => 'ajax']);
        $form->addSelect('from', 'From', $data)
			 ->setAttribute('class', 'selectize_dropdown');
        $form->addSelect('to', 'To', $data);
        $form->addText('date', 'Date of departure')->setRequired(true);
        $form->addText('time', 'Time of departure')->setRequired(true);
        $form->addText('passengers', 'Passengers')
             ->setDefaultValue(1)
             ->setAttribute('number')
             ->setRequired('We need to know how many passengers is going to travel')
             ->addRule(Form::MIN, 'There has to at least one passenger', 1)
             ->addRule(Form::MAX, 'We can\'t transfer more people', $maximalCapacity)
             ->addRule(Form::NUMERIC, 'Passengers have to be number');
		$form->addSubmit('submit', 'See prices')
			 ->setAttribute('class', 'ajax');
		$form->setDefaults($this->setDefaultData());
		$form->onValidate[] = function ($form) {
			$this->validateForm($form);
		};
		$form->onSuccess[] = function ($form) {
			$this->formSuccess($form);
		};
		return $form;
	}

	/**
	 * @param Form $form
	 *
	 * @throws AbortException
	 */
	private function formSuccess(Form $form): void
	{
		$values = $form->getValues(true);
		$date = new DateTime($values['date']);
		$time = new DateTime($values['time']);
		$passengers = $values['passengers'] > 0 ? $values['passengers'] : 0;

		try {
			$cartHash = $this->handler->save($values['from'], $values['to'], $date, $time, $passengers);
			$this->onSuccess($cartHash);
		} catch (DestinationNotFoundException $exception) {
			Debugger::log($exception);
			($this->onError());
		} catch (RouteNotFoundException $exception) {
			Debugger::log($exception);
			($this->onError());
		} catch (\Exception $exception) {
			if ($exception instanceof AbortException) {
				throw $exception;
			}
			Debugger::log($exception);
			($this->onError());
		}
	}

	private function validateForm(Form $form): void
	{
		$values = $form->getValues();
		if ($values->from === $values->to) {
			$form->addError('Departure and destination cannot be same');
		}
		$departureDate = new DateTime($values->date);
		if ($departureDate <= $this->dateTimeService->getActualDateTime()) {
			$form->addError('Departure date is invalid');
		}
		$this->redrawControl('routeForm');
	}

	private function setDefaultData(): ?array
	{
		$departureDate = $this->customerDefaultData->getDepartureDate();
		$departureTime = $this->customerDefaultData->getDepartureTime();

		return [
			'from'       => $this->customerDefaultData->getFromId(),
			'to'         => $this->customerDefaultData->getToId(),
			'passengers' => $this->customerDefaultData->getPassengers(),
			'date'   => $departureDate === null ? null : $departureDate->format(self::DATE_INPUT_FORMAT),
			'time'   => $departureTime === null ? null : $departureTime->format(self::TIME_INPUT_FORMAT),
		];
	}
}
