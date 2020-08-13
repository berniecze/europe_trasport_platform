<?php
declare(strict_types=1);

namespace App\Model\Cart;

use App\Model\DateTimeService;

class CartValidityService
{

	/**
	 * @var CartRepository
	 */
	private $repository;

	/**
	 * @var DateTimeService
	 */
	private $dateTimeService;

	public function __construct(CartRepository $repository, DateTimeService $dateTimeService)
	{
		$this->repository = $repository;
		$this->dateTimeService = $dateTimeService;
	}

	public function isValid(string $cartHash): bool
	{
		try {
			$cart = $this->repository->getByHash($cartHash);
			$today = DateTimeService::convertImmutableToMutable($this->dateTimeService->getActualDateTime());

			return !($cart->getDate() <= $today->modify('2 days'));
		} catch (\Exception $exception) {
			return false;
		}
	}
}