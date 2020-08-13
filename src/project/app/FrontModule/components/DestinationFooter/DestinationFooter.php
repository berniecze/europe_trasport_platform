<?php
declare(strict_types=1);

namespace App\FrontModule\Components\DestinationFooter;

use App\Model\Destination\Exceptions\DestinationNotFoundException;
use Nette\Application\UI\Control;

class DestinationFooter extends Control
{

	/**
	 * @var DataProvider
	 */
	private $dataProvider;

	public function __construct(DataProvider $dataProvider)
	{
		parent::__construct();
		$this->dataProvider = $dataProvider;
	}

	public function render()
	{
		$template = $this->getTemplate();
		$template->setFile(__DIR__ . '/default.latte');

		try {
			$favouriteDestinations = $this->dataProvider->getAllDestinations();
			$company = $this->dataProvider->getCompany();
			if ($company !== null) {
				$this->template->companyName = $company->getName();
			} else {
				$this->template->companyName = null;
			}
		} catch (DestinationNotFoundException $exception) {
			$favouriteDestinations = [];
		}
		$this->template->destinations = $favouriteDestinations;
		$this->template->year = (new \DateTime())->format('Y');
		$template->render();
	}
}
