<?php
declare(strict_types=1);

namespace App\AdminModule\Presenters;

use App\AdminModule\Components\ChooseDriverForm\ChooseDriverFormControl;
use App\AdminModule\Components\ChooseDriverForm\ChooseDriverFormFactory;
use App\AdminModule\Components\ClientOrderForm\ClientOrderFormControl;
use App\AdminModule\Components\ClientOrderForm\ClientOrderFormFactory;
use App\AdminModule\Components\OrderList\IClientOrdersFactory;
use App\AdminModule\Components\OrderList\OrderList;
use App\Model\ClientOrder\ClientOrder;
use App\Model\ClientOrder\ClientOrderRepository;
use App\Model\ClientOrder\ClientOrderService;
use App\Model\Company\CompanyRepository;
use App\Model\Company\Exceptions\CompanyNotFoundException;
use Exception;
use Joseki\Application\Responses\PdfResponse;
use Nette\Application\AbortException;
use Tracy\Debugger;

class ClientOrderPresenter extends AdminBasePresenter
{

	/**
	 * @var IClientOrdersFactory
	 */
	private $clientOrdersFactory;

	/**
	 * @var CompanyRepository
	 */
	private $companyRepository;

	/**
	 * @var CompanyRepository
	 */
	private $clientOrderRepository;

	/**
	 * @var ClientOrderService
	 */
	private $clientOrderService;

	/**
	 * @var ClientOrderFormFactory
	 */
	private $clientOrderFormFactory;

	/**
	 * @var ChooseDriverFormFactory
	 */
	private $chooseDriverFormFactory;

	public function __construct(
		IClientOrdersFactory $clientOrdersFactory,
		ClientOrderRepository $clientOrderRepository,
		CompanyRepository $companyRepository,
		ClientOrderService $clientOrderService,
		ClientOrderFormFactory $clientOrderFormFactory,
		ChooseDriverFormFactory $chooseDriverFormFactory
	) {
		parent::__construct();
		$this->clientOrdersFactory = $clientOrdersFactory;
		$this->clientOrderRepository = $clientOrderRepository;
		$this->companyRepository = $companyRepository;
		$this->clientOrderService = $clientOrderService;
		$this->clientOrderFormFactory = $clientOrderFormFactory;
		$this->chooseDriverFormFactory = $chooseDriverFormFactory;
	}

	public function createComponentNotAcceptedList(): OrderList
	{
		return $this->clientOrdersFactory->create(ClientOrder::NOT_ACCEPTED_ORDERS);
	}

	public function createComponentAcceptedList(): OrderList
	{
		return $this->clientOrdersFactory->create(ClientOrder::ACCEPTED_ORDERS);
	}

	public function createComponentAllOrdersList(): OrderList
	{
		return $this->clientOrdersFactory->create(null);
	}

	public function createComponentOrderForm(): ClientOrderFormControl
	{
		$onSuccess = function () {
			$this->flashMessage('Order saved');
			$this->redirect('ClientOrder:default');
		};
		$onError = function () {
			$this->flashMessage('An error happened. Please try again later.');
			$this->redirect('this');
		};

		return $this->clientOrderFormFactory->create($onSuccess, $onError);
	}

	public function createComponentChooseDriverForm(): ChooseDriverFormControl
	{
		$onSuccess = function () {
			$this->flashMessage('Driver assigned');
			$this->redirect('ClientOrder:default');
		};
		$onError = function () {
			$this->flashMessage('An error happened. Please try again later.');
			$this->redirect('this');
		};

		return $this->chooseDriverFormFactory->create($onSuccess, $onError);
	}

	public function actionRenderInvoice($orderId)
	{
		if ($orderId === null || is_numeric($orderId)) {
			$this->flashMessage('Unknown id for order invoice');
		}
		try {
			$order = $this->clientOrderRepository->getById((int)$orderId);

			if ($order === null) {
				$this->flashMessage('Unknown id for order invoice');
				$this->redirect('accepted');
			}
			$company = $this->companyRepository->getCompany();
			$template = $this->createTemplate();
			$template->setFile(__DIR__ . "/templates/ClientOrder/renderInvoice.latte");

			$template->name = $company->getName();
			$template->ico = $company->getIdentificationNumber();
			$template->dic = $company->getTax();
			$template->address = $company->getAddress();

			$template->clientName = $order->getCart()->getClient()->getName();
			$template->clientLastname = $order->getCart()->getClient()->getLastname();
			$template->clientEmail = $order->getCart()->getClient()->getEmail();
			$template->finalPrice = $order->getFinalPrice();
			$template->orderNumber = $order->getNumber();
			$template->created = $order->getCreated();
			$template->from = $order->getCart()->getRoute()->getDeparture()->getName();
			$template->to = $order->getCart()->getRoute()->getArrival()->getName();
			$template->dueTo = $order->getCreated()->modify('+ 14 days');

			$pdf = new PdfResponse($template);
			$pdf->pageFormat = "A4";
			$pdf->setSaveMode(PdfResponse::DOWNLOAD);
			$pdf->setDocumentTitle(sprintf('transport invoice %s', $order->getNumber()));
			$this->sendResponse($pdf);

		} catch (CompanyNotFoundException $exception) {
			Debugger::log($exception);
			$this->flashMessage('Unknown id for order invoice');
			$this->redirect('accepted');
		}
	}

	public function actionAssignDriver($orderId): void
	{
		if ($orderId === null) {
			$this->flashMessage('Unknown id for order administration');
			$this->redirect('default');
		}
		$clientOrder = $this->clientOrderRepository->getById((int)$orderId);
		if ($clientOrder === null) {
			$this->flashMessage('Order does not exists');
			$this->redirect('default');
		}

		$driverName = null;
		$driverPhone = null;
		if ($clientOrder->getDriver() !== null) {
			$driverName = $clientOrder->getDriver()->getSurname();
			$driverPhone = $clientOrder->getDriver()->getPhone();
		}
		$client = $clientOrder->getCart()->getClient();
		$transport = $clientOrder->getCart()->getTransport();
		$this->template->id = $clientOrder->getId();
		$this->template->name = $client->getName() . ' ' . $client->getLastname();
		$this->template->transportName = $transport->getName();
		$this->template->email = $client->getEmail();
		$this->template->phone = $client->getPhone();
		$this->template->ticketNumber = $client->getTicketNumber();
		$this->template->transportDate = $clientOrder->getCart()->getDate();
		$this->template->from = $clientOrder->getCart()->getRoute()->getDeparture()->getName();
		$this->template->to = $clientOrder->getCart()->getRoute()->getArrival()->getName();
		$this->template->orderNumber = $clientOrder->getNumber();
		$this->template->driverName = $driverName;
		$this->template->driverPhone = $driverPhone;

		$this['chooseDriverForm']['form']->setDefaults(['order_id' => $clientOrder->getId()]);
	}

	public function actionEdit($orderId)
	{
		if ($orderId === null) {
			$this->flashMessage('Unknown id for order administration');
			$this->redirect('default');
		}
		$clientOrder = $this->clientOrderRepository->getById((int)$orderId);

		if ($clientOrder === null) {
			$this->flashMessage('Order does not exists');
			$this->redirect('default');
		}

		$driverName = null;
		$driverPhone = null;
		if ($clientOrder->getDriver() !== null) {
			$driverName = $clientOrder->getDriver()->getSurname();
			$driverPhone = $clientOrder->getDriver()->getPhone();
		}
		$client = $clientOrder->getCart()->getClient();
		$transport = $clientOrder->getCart()->getTransport();
		$this->template->id = $clientOrder->getId();
		$this->template->name = $client->getName() . ' ' . $client->getLastname();
		$this->template->transportName = $transport->getName();
		$this->template->email = $client->getEmail();
		$this->template->phone = $client->getPhone();
		$this->template->ticketNumber = $client->getTicketNumber();
		$this->template->transportDate = $clientOrder->getCart()->getDate();
		$this->template->from = $clientOrder->getCart()->getRoute()->getDeparture()->getName();
		$this->template->to = $clientOrder->getCart()->getRoute()->getArrival()->getName();
		$this->template->orderNumber = $clientOrder->getNumber();
		$this->template->driverName = $driverName;
		$this->template->driverPhone = $driverPhone;

		$defaultValues = [
			'id'          => $clientOrder->getId(),
			'status'      => $clientOrder->getStatus(),
			'number'      => $clientOrder->getNumber(),
			'final_price' => $clientOrder->getFinalPrice(),
		];
		$this['orderForm']['form']->setDefaults($defaultValues);
	}

	public function actionAccept($clientOrderId): void
	{
		if ($clientOrderId === null) {
			$this->flashMessage('Unknown id for order administration');
			$this->redirect('ClientOrder:notAccepted');
		}
		$order = $this->clientOrderRepository->getById((int)$clientOrderId);

		if ($order === null) {
			$this->flashMessage('Client\'s order does not exists');
			$this->redirect('ClientOrder:notAccepted');
		}

		try {
			$order->setStatus(ClientOrder::ACCEPTED_ORDERS);
			$this->clientOrderService->save($order);

			$this->redirect('ClientOrder:assignDriver', $order->getId());
			$this->flashMessage('Client\'s order successfully approved for transport', 'success');
		} catch (Exception $exception) {
			if ($exception instanceof AbortException) {
				throw $exception;
			}
			$this->flashMessage('Error happened while changing status. Please try again later', 'error');
			$this->redirect('ClientOrder:notAccepted');
		}
	}
}
