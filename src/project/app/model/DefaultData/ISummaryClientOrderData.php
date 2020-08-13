<?php
declare(strict_types=1);

namespace App\Model\DefaultData;

use \DateTime;

interface ISummaryClientOrderData
{
	public function getPickupDate(): Datetime;

	public function getPickupTime(): Datetime;

	public function getFrom(): string;

	public function getTo(): string;

	public function isTwoWay(): bool;

	public function getReturnPickupDate(): ?DateTime;

	public function getReturnPickupTime(): ?DateTime;

	public function getTransportName(): string;

	public function getPassengers(): int;

	public function getClientName(): string;

	public function getClientPhone(): string;

	public function getFinalPrice(): float;

	public function getClientOrderId(): int;
}
