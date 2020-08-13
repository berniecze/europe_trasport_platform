<?php

declare(strict_types=1);

namespace App\FrontModule\Presenters;

use App\FrontModule\Components\DestinationFooter\DestinationFooter;
use App\FrontModule\Components\DestinationFooter\IDestinationFooterFactory;
use App\Model\Company\CompanyRepository;
use App\Model\Company\Exceptions\CompanyNotFoundException;
use App\Model\DateTimeService;
use App\Model\DefaultData\DefaultDataService;
use Nette;

/**
 * Base presenter for all application presenters.
 */
abstract class BasePresenter extends Nette\Application\UI\Presenter
{
	public const DEFAULT_COMPANY_TITLE = 'Transfers - your private taxi';

	/**
	 * @inject
	 * @var DateTimeService $dateTimeService
	 */
	public $dateTimeService;

	/**
	 * @inject
	 * @var IDestinationFooterFactory $destinationFactory
	 */
	public $destinationFactory;

	/**
	 * @inject
	 * @var CompanyRepository $companyRepository
	 */
	public $companyRepository;

	/**
	 * @inject
	 * @var DefaultDataService $defaultDataService
	 */
	public $defaultDataService;

	public const USER_COOKIE_CART_HASH   = 'cart-hash';
	public const USER_COOKIE_CART_EXPIRE = 'cart-hash';

	public function createComponentDestinationFooter(): DestinationFooter
	{
		return $this->destinationFactory->create();
	}

	protected function getCookieExpiration()
	{
		try {
			$datetime = DateTimeService::convertImmutableToMutable($this->dateTimeService->getActualDateTime());
			return $datetime->modify('2 days');
		} catch (\Exception $exception) {
			return (new \DateTime())->modify('2 days');
		}
	}

	public function startup()
	{
		parent::startup();
		$this->template->companyName = $this->getCompanyNameForTitle();
	}

	public function getCompanyNameForTitle(): string
	{
		try {
			$company = $this->companyRepository->getCompany();
			return $company->getName();
		} catch (CompanyNotFoundException $exception) {
			return self::DEFAULT_COMPANY_TITLE;
		}
	}

	protected function isCartHashValid(): ?string
	{
		$cartHash = $this->getHttpRequest()->getCookie(self::USER_COOKIE_CART_HASH);
		if ($cartHash === null || strlen($cartHash) === 0) {
			return null;
		}

		return (string) $cartHash;
	}
}
