<?php
declare(strict_types=1);

namespace App\Model\DefaultData;

use App\Model\Cart\Cart;
use App\Model\Cart\CartRepository;
use App\Model\Cart\Exceptions\CartNotFoundException;
use App\Model\DateTimeService;
use App\Model\DefaultData\Exceptions\ExpiredCartException;
use App\Model\DefaultData\Exceptions\InvalidCartHashException;
use App\Model\DefaultData\Exceptions\InvalidCartStatusException;
use Exception;

class DefaultDataService
{

	/**
	 * @var CartRepository $cartRepository
	 */
	private $cartRepository;

	/**
	 * @var DateTimeService $dateTimeService
	 */
	private $dateTimeService;

	public function __construct(
		CartRepository $cartRepository,
		DateTimeService $dateTimeService
	) {
		$this->cartRepository = $cartRepository;
		$this->dateTimeService = $dateTimeService;
	}

	/**
	 * @param string $cartHash
	 *
	 * @return CustomerDefaultData
	 * @throws CartNotFoundException
	 * @throws ExpiredCartException
	 * @throws InvalidCartHashException
	 * @throws InvalidCartStatusException
	 */
	public function getDefaultData(string $cartHash): CustomerDefaultData
	{
		$this->validateCartHash($cartHash);

		$cart = $this->cartRepository->getByHash($cartHash);
		$this->validateCart($cart);

		return new CustomerDefaultData($cart->getRoute()->getDeparture()->getId(),
			$cart->getRoute()->getArrival()->getId(), $cart->getPassengers(), $cart->getDate(), $cart->getTime(),
			$cart->getHash());
	}

	/**
	 * @param string $cartHash
	 *
	 * @return ISummaryClientOrderData
	 * @throws CartNotFoundException
	 * @throws ExpiredCartException
	 * @throws InvalidCartHashException
	 */
	public function getSummaryData(string $cartHash): ISummaryClientOrderData
	{
		$this->validateCartHash($cartHash);
		$cart = $this->cartRepository->getByHash($cartHash);
		if ($this->isCartExpired($cart)) {
			throw new ExpiredCartException();
		}

		return new SummaryOrderData($cart);
	}

	public function getPageData(int $destinationId, ?string $cartHash): DefaultDataInterface
	{
		if ($cartHash === null) {
			return new PageDefaultData(null, $destinationId);
		}
		try {
			return $this->getDefaultData($cartHash);
		} catch (Exception $exception) {
			return new PageDefaultData(null, $destinationId);
		}
	}

	/**
	 * @param string $cartHash
	 *
	 * @throws InvalidCartHashException
	 */
	private function validateCartHash(string $cartHash): void
	{
		if ($cartHash === '') {
			throw new InvalidCartHashException();
		}

	}

	/**
	 * @param Cart $cart
	 *
	 * @throws Exception
	 * @throws ExpiredCartException
	 * @throws InvalidCartStatusException
	 */
	private function validateCart(Cart $cart): void
	{
		if (!$this->isCartStatusValid($cart)) {
			throw new InvalidCartStatusException();
		}

		if ($this->isCartExpired($cart)) {
			throw new ExpiredCartException();
		}
	}

	private function isCartStatusValid(Cart $cart): bool
	{
		if ($cart->getStatus() !== Cart::STATUS_CREATED) {
			return false;
		}
		return true;
	}

	private function isCartExpired(Cart $cart): bool
	{
		$today = DateTimeService::convertImmutableToMutable($this->dateTimeService->getActualDateTime());
		if ($cart->getDate() <= $today->modify('2 days')) {
			return true;
		}
		return false;
	}
}
