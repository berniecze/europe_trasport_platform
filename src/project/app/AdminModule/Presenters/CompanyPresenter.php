<?php
declare(strict_types=1);

namespace App\AdminModule\Presenters;

use App\AdminModule\Components\CompanyForm\FormControl;
use App\AdminModule\Components\CompanyForm\ICompanyFormFactory;
use App\Model\Company\CompanyRepository;
use Tracy\Debugger;
use Tracy\ILogger;

class CompanyPresenter extends AdminBasePresenter
{

	/**
	 * @var CompanyRepository $companyRepository
	 */
	private $companyRepository;

	/**
	 * @var ICompanyFormFactory $companyFormFactory
	 */
	private $companyFormFactory;

	public function __construct(CompanyRepository $companyRepository, ICompanyFormFactory $companyFormFactory)
	{
		parent::__construct();
		$this->companyRepository = $companyRepository;
		$this->companyFormFactory = $companyFormFactory;
	}

	public function renderEdit()
	{
		try {
			$company = $this->companyRepository->getCompany();

			$this['companyForm']['form']->setDefaults([
				'id' => $company->getId(),
				'name' => $company->getName(),
				'address' => $company->getAddress(),
				'identification_number' => $company->getIdentificationNumber(),
			]);
		} catch (\Exception $exception) {
			Debugger::log($exception, ILogger::CRITICAL);
			$this->flashMessage('Company does not exists');
			$this->redirect('Home:default');
		}
	}

	public function createComponentCompanyForm(): FormControl
	{
		$onSuccess = function () {
			$this->flashMessage('Company changes saved');
			$this->redirect('Homepage:default');
		};

		$onError = function () {
			$this->flashMessage('An error happened. Please try again later.');
			$this->redirect('this');
		};

		return $this->companyFormFactory->create($onSuccess, $onError);
	}
}
