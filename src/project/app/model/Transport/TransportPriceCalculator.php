<?php
declare(strict_types=1);

namespace App\Model\Transport;

use App\Model\Cart\Cart;
use App\Model\Cart\CartRepository;
use App\Model\Cart\Exceptions\CartNotFoundException;
use App\Model\ClientOrder\ClientOrder;
use DateTime;
use Exception;

class TransportPriceCalculator
{

	/**
	 * @var CartRepository
	 */
	private $cartRepository;

	public function __construct(CartRepository $cartRepository) {
		$this->cartRepository = $cartRepository;
	}

	/**
	 * @param Cart $cart
	 * @param Transport|null $specificTransport
	 *
	 * @return float
	 */
	public function getTotalPrice(Cart $cart, ?Transport $specificTransport = null): float
	{
		if ($specificTransport) {
			$additionalTransportPrice = $specificTransport->getFixedPrice();
			$multiplierTransportPrice = $specificTransport->getMultiplierPrice();

		} else {
			$additionalTransportPrice = $cart->getTransport()->getFixedPrice();
			$multiplierTransportPrice = $cart->getTransport()->getMultiplierPrice();

		}
		$highDemandMultiplier = 1.0;
		if ($this->isSameDayOrderAccepted($cart->getDate())) {
			$highDemandMultiplier = 1.1;
		}
		$totalPrice = $cart->getRoute()->getPrice() + (($cart->getRoute()->getPrice() * $multiplierTransportPrice) + $additionalTransportPrice) * $highDemandMultiplier;
		return round($totalPrice, 0);
	}

	public function isSameDayOrderAccepted(DateTime $dateTime): bool
	{
		try {
			$carts = $this->cartRepository->getByTransferDate($dateTime);

			foreach ($carts as $cart) {
				/** @var \App\Model\Cart\Cart $cart */
				if ($cart->getOrder() !== null && $cart->getOrder()->getStatus() === ClientOrder::ACCEPTED_ORDERS) {
					return true;
				}
			}
			return false;
		} catch (Exception $exception) {
			if ($exception instanceof CartNotFoundException) {
				return false;
			}
		}
		return false;
	}
}
