<?php
declare(strict_types=1);

namespace App\FrontModule\Components\CartAddressForm;

use App\Model\Cart\Cart;
use App\Model\Cart\CartRepository;
use App\Model\Cart\Exceptions\CartNotFoundException;
use App\Model\Cart\Exceptions\InvalidCartException;
use App\Model\DateTimeService;

class DataProvider
{

	/**
	 * @var CartRepository
	 */
	private $cartRepository;

	/**
	 * @var DateTimeService
	 */
	private $dateTimeService;

	public function __construct(CartRepository $cartRepository, DateTimeService $dateTimeService)
	{
		$this->cartRepository = $cartRepository;
		$this->dateTimeService = $dateTimeService;
	}

	/**
	 * @param string $cartHash
	 *
	 * @return Cart|null
	 * @throws InvalidCartException
	 */
	public function getValidCart(string $cartHash): ?Cart
	{
		try {
			$cart = $this->cartRepository->getByHash($cartHash);
			$today = DateTimeService::convertImmutableToMutable($this->dateTimeService->getActualDateTime());

			if ($cart->getDate() <= $today->modify('2 days')) {
				throw InvalidCartException::byHash($cartHash);
			}
			return $cart;
		} catch (CartNotFoundException $exception) {
			//do nothing
		}
		return null;
	}
}