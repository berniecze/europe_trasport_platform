<?php
declare(strict_types=1);

namespace App\AdminModule\Components\OrderList;

use App\Model\ClientOrder\ClientOrder;
use App\Model\ClientOrder\Exceptions\ClientOrderNotFoundException;
use Kdyby\Doctrine\ResultSet;
use Nette\Application\UI\Control;

class OrderList extends Control
{

	/**
	 * @var int
	 */
	public $viewType;

	/**
	 * @var int
	 */
	public $dataProvider;

	public function __construct(DataProvider $dataProvider, ?int $viewType = ClientOrder::ACCEPTED_ORDERS)
	{
		parent::__construct();
		$this->dataProvider = $dataProvider;
		$this->viewType = $viewType;
	}

	public function render(): void
	{
		$this->template->setFile(__DIR__ . '/default.latte');

		try {
			$orders = $this->getOrders();
			$this->template->orders = $orders;
			$this->template->acceptedStatus = ClientOrder::ACCEPTED_ORDERS;
			$this->template->notAcceptedStatus = ClientOrder::NOT_ACCEPTED_ORDERS;
			$this->template->cancelledStatus = ClientOrder::CANCELLED_ORDERS;
			$this->template->finishedStatus = ClientOrder::FINISHED_ORDERS;
		} catch (ClientOrderNotFoundException $exception) {
			$this->template->orders = [];
		}

		$this->template->render();
	}

	/**
	 * @return ResultSet
	 * @throws ClientOrderNotFoundException
	 */
	private function getOrders(): ResultSet
	{
		switch ($this->viewType) {
			case ClientOrder::ACCEPTED_ORDERS:
				return $this->dataProvider->getAcceptedOrders();
			case ClientOrder::NOT_ACCEPTED_ORDERS:
				return $this->dataProvider->getNotAcceptedOrders();
			default:
				return $this->dataProvider->getAllOrders();
		}
	}
}
