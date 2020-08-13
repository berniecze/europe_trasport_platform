<?php
declare(strict_types=1);

namespace App\AdminModule\Presenters;

use App\Model\ClientOrder\ClientOrder;
use App\Model\ClientOrder\ClientOrderRepository;
use App\Model\ClientOrder\Exceptions\ClientOrderNotFoundException;
use App\Model\DateTimeService;
use Kdyby\Doctrine\ResultSet;
use Nette\Utils\DateTime;

class HomepagePresenter extends AdminBasePresenter
{

	/**
	 * @var ClientOrderRepository
	 */
	private $clientOrderRepository;

	/**
	 * @var DateTimeService
	 */
	private $dateTimeService;

	public function __construct(ClientOrderRepository $clientOrderRepository, DateTimeService $dateTimeService)
	{
		parent::__construct();
		$this->clientOrderRepository = $clientOrderRepository;
		$this->dateTimeService = $dateTimeService;
	}

	public function actionDefault(): void
	{
		$date = new DateTime('first day of this month');
		try {
			$orders = $this->clientOrderRepository->getFinishedByDate($date);
			$totalIncome = 0;
			$numberOfTransfers = 0;
			foreach ($orders as $order) {
				/** @var ClientOrder $order */
				$totalIncome += $order->getFinalPrice();
				$numberOfTransfers++;
			}

			$this->template->totalIncome = $totalIncome;
			$this->template->numberOfTransfers = $numberOfTransfers;
			$this->template->averageRevenue = $totalIncome / $numberOfTransfers;
		} catch (ClientOrderNotFoundException $exception) {
			$this->template->totalIncome = 0;
			$this->template->numberOfTransfers = 0;
			$this->template->averageRevenue = 0;
		}

		try {
			$notAcceptedOrders = $this->clientOrderRepository->notAcceptedOrderedByDate($this->dateTimeService->getActualDateTime());

			$this->template->notAcceptedOrdersAmount = count($notAcceptedOrders);
			$this->template->notAcceptedOrders = $this->getOrdersForList($notAcceptedOrders);
		} catch (\Exception $exception) {
			$this->template->notAcceptedOrders = [];
			$this->template->notAcceptedOrdersAmount = 0;
		}

		try {
			$acceptedOrders = $this->clientOrderRepository->acceptedOrderedByDate($this->dateTimeService->getActualDateTime());

			$this->template->acceptedOrdersAmount = $this->getOrdersForList($acceptedOrders);
			$this->template->acceptedOrders = $this->getOrdersForList($acceptedOrders);
		} catch (\Exception $exception) {
			$this->template->acceptedOrders = [];
			$this->template->acceptedOrdersAmount = 0;
		}
	}

	/**
	 * @param ResultSet $orders
	 *
	 * @return array
	 */
	private function getOrdersForList(ResultSet $orders): array
	{
		$data = [];
		$i = 0;

		foreach ($orders as $order) {
			/** @var ClientOrder $order */
			$departureName = $order->getCart()->getRoute()->getDeparture()->getName();
			$arrivalName = $order->getCart()->getRoute()->getArrival()->getName();
			$data[] = [
				'id' => $order->getId(),
				'date' => $order->getCart()->getDate(),
				'price' => $order->getFinalPrice(),
				'passengers' => $order->getCart()->getPassengers(),
				'route' => sprintf('%s to %s', $departureName, $arrivalName),
			];
			$i++;
			if ($i === 9) {
				break;
			}
		}
		return $data;
	}
}
