<?php
declare(strict_types=1);

namespace App\AdminModule\Components\OrderList;

use App\Model\ClientOrder\ClientOrder;

interface IClientOrdersFactory
{

	public function create(?int $viewType = ClientOrder::NOT_ACCEPTED_ORDERS): OrderList;
}
