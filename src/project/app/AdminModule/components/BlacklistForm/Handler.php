<?php
declare(strict_types=1);

namespace App\AdminModule\Components\BlacklistForm;

use Application\Blacklist\Request\CreateBlacklistRequest;
use Application\Blacklist\Request\UpdateBlacklistRequest;
use Application\Blacklist\UseCase\CreateBlacklistUseCase;
use Application\Blacklist\UseCase\UpdateBlacklistUseCase;
use Application\Transport\Request\GetTransportRequest;
use Application\Transport\UseCase\GetTransportUseCase;
use DateTimeImmutable;
use Domain\Entity\Blacklist\ValueObject\FromDate;
use Domain\Entity\Blacklist\ValueObject\ToDate;
use Exception;
use Infrastructure\Exception\TransportNotFoundException;

class Handler
{

	/**
	 * @var GetTransportUseCase
	 */
	private $getTransportUseCase;

	/**
	 * @var CreateBlacklistUseCase
	 */
	private $createBlacklistUseCase;

	/**
	 * @var UpdateBlacklistUseCase
	 */
	private $updateBlacklistUseCase;

	public function __construct(
	    GetTransportUseCase $getTransportUseCase,
        CreateBlacklistUseCase $createBlacklistUseCase,
        UpdateBlacklistUseCase $updateBlacklistUseCase
    ) {
		$this->getTransportUseCase = $getTransportUseCase;
		$this->createBlacklistUseCase = $createBlacklistUseCase;
		$this->updateBlacklistUseCase = $updateBlacklistUseCase;
	}

	/**
	 * @param array $values
	 *
	 * @throws Exception
	 * @throws TransportNotFoundException
     */
	public function handle(array $values): void
	{
		if ($values['id'] === '') {
			$this->createNewBlacklist($values);
		} else {
            $this->updateBlacklist($values);
		}
	}

    /**
     * @param array $values
     *
     * @throws TransportNotFoundException
     * @throws Exception
     */
    private function createNewBlacklist(array $values): void
    {
        $transportRequest = new GetTransportRequest((int) $values['transport']);
        $transport = $this->getTransportUseCase->execute($transportRequest);
        $createBlacklistRequest = new CreateBlacklistRequest(
            $transport,
            new FromDate(new DateTimeImmutable($values['from_date'])),
            new ToDate(new DateTimeImmutable($values['to_date']))
        );

        $this->createBlacklistUseCase->execute($createBlacklistRequest);
	}

    /**
     * @param array $values
     *
     * @throws TransportNotFoundException
     * @throws Exception
     */
    private function updateBlacklist(array $values): void
    {
        $transportRequest = new GetTransportRequest((int) $values['transport']);
        $transport = $this->getTransportUseCase->execute($transportRequest);

        $updateBlacklistRequest = new UpdateBlacklistRequest(
            (int) $values['id'],
            $transport,
            new FromDate(new DateTimeImmutable($values['from_date'])),
            new ToDate(new DateTimeImmutable($values['to_date']))
        );

        $this->updateBlacklistUseCase->execute($updateBlacklistRequest);
	}
}
