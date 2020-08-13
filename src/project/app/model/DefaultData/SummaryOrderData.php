<?php
declare(strict_types=1);

namespace App\Model\DefaultData;

use App\Model\Cart\Cart;
use DateTime;

class SummaryOrderData implements ISummaryClientOrderData
{
	/**
	 * @var Cart $cart
	 */
	private $cart;

	public function __construct(Cart $cart)
	{
		$this->cart = $cart;
	}

	public function getFrom(): string
	{
		return $this->cart->getClient()->getFromAddress();
	}

	public function getTo(): string
	{
		return $this->cart->getClient()->getToAddress();
	}

	public function getPickupDate(): DateTime
	{
		return $this->cart->getDate();
	}

	public function getPickupTime(): DateTime
	{
		return $this->cart->getTime();
	}

	public function getPassengers(): int
	{
		return $this->cart->getPassengers();
	}

	public function getClientName(): string
	{
		return sprintf('%s %s', $this->cart->getClient()->getName(), $this->cart->getClient()->getLastname());
	}

	public function getClientPhone(): string
	{
		return $this->cart->getClient()->getPhone();
	}

	public function isTwoWay(): bool
	{
		if ($this->cart->getClient()->getReturnDepartureDatetime()) {
			return true;
		}
		return false;
	}

	//TODO fix to date only
	public function getReturnPickupDate(): ?DateTime
	{
		return $this->cart->getClient()->getReturnDepartureDatetime();
	}

	// TODO fix to time
	public function getReturnPickupTime(): ?DateTime
	{
		return $this->cart->getClient()->getReturnDepartureDatetime();
	}

	public function getTransportName(): string
	{
		return $this->cart->getTransport()->getName();
	}

	// TODO fix loading from DB
	public function getFinalPrice(): float
	{
		return 0.0;
	}

	public function getClientOrderId(): int
	{
		return $this->cart->getOrder()->getId();
	}
}
