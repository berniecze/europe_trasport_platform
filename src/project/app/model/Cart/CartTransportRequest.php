<?php
declare(strict_types=1);

namespace App\Model\Cart;

class CartTransportRequest
{

	/**
	 * @var string
	 */
	public $cartHash;

	/**
	 * @var int
	 */
	public $transportId;

	public function __construct(string $cartHash, int $transportId)
	{
		$this->cartHash = $cartHash;
		$this->transportId = $transportId;
	}

	public function getCartHash(): string
	{
		return $this->cartHash;
	}

	public function getTransportId(): int
	{
		return $this->transportId;
	}
}
