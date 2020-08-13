<?php
declare(strict_types=1);

namespace App\AdminModule\Components\TransportForm;

use App\Model\PhotoService;
use Application\Transport\Request\CreateTransportRequest;
use Application\Transport\Request\UpdateTransportRequest;
use Application\Transport\UseCase\CreateTransportUseCase;
use Application\Transport\UseCase\UpdateTransportUseCase;
use Domain\Entity\Transport\ValueObject\Active;
use Domain\Entity\Transport\ValueObject\Capacity;
use Domain\Entity\Transport\ValueObject\Description;
use Domain\Entity\Transport\ValueObject\FixedPrice;
use Domain\Entity\Transport\ValueObject\Luggage;
use Domain\Entity\Transport\ValueObject\MultiplierPrice;
use Domain\Entity\Transport\ValueObject\Name;
use Domain\Entity\Transport\ValueObject\PhotoUrl;
use Exception;
use Infrastructure\Exception\TransportNotFoundException;
use Nette\Http\FileUpload;

class Handler
{

	/**
	 * @var UpdateTransportUseCase
	 */
	private $updateTransportUseCase;

	/**
	 * @var CreateTransportUseCase
	 */
	private $createTransportUseCase;

	/**
	 * @var PhotoService
	 */
	private $photoService;

	public function __construct(
	    UpdateTransportUseCase $updateTransportUseCase,
        CreateTransportUseCase $createTransportUseCase,
        PhotoService $photoService
    ) {
		$this->updateTransportUseCase = $updateTransportUseCase;
		$this->createTransportUseCase = $createTransportUseCase;
		$this->photoService = $photoService;
	}

    /**
     * @param array $values
     *
     * @throws TransportNotFoundException
     */
	public function handle(array $values): void
	{
	    /** @var FileUpload $uploadedPhoto */
	    $uploadedPhoto = $values['photo'];
	    $photoFileName = $this->resolvePhoto($uploadedPhoto);

	    if ($values['id'] === "") {
            $this->createNewTransport($values, $photoFileName);
        } else {
            $this->updateTransport($values, $photoFileName);
        }
	}

    /**
     * @param array $values
     * @param string|null $photoFileName
     *
     * @throws Exception
     */
    private function createNewTransport(array $values, ?string $photoFileName): void
    {
        $request = new CreateTransportRequest(
            new Active((bool) $values['active']),
            new Name((string) $values['name']),
            new Capacity((int) $values['capacity']),
            new Luggage((int) $values['luggage']),
            new FixedPrice((float) $values['fixedPrice']),
            new MultiplierPrice((float) $values['multiplierPrice'])
        );

        if ($photoFileName !== null) {
            $request->setPhotoUrl(new PhotoUrl((string) $photoFileName));
        }

        if ($values['description'] !== null && $values['description'] !== '') {
            $request->setDescription(new Description((string) $values['description']));
        }

        $this->createTransportUseCase->execute($request);
	}

    /**
     * @param array $values
     * @param string|null $photoFileName
     *
     * @throws TransportNotFoundException
     * @throws Exception
     */
    private function updateTransport(array $values, ?string $photoFileName): void
    {
        $request = new UpdateTransportRequest(
            (int) $values['id'],
            new Active((bool) $values['active']),
            new Name((string) $values['name']),
            new Capacity((int) $values['capacity']),
            new Luggage((int) $values['luggage']),
            new FixedPrice((float) $values['fixedPrice']),
            new MultiplierPrice((float) $values['multiplierPrice'])
        );

        if ($values['description'] !== null && $values['description'] !== '') {
            $request->setDescription(new Description((string) $values['description']));
        }

        if ($photoFileName !== null) {
            $request->setPhotoUrl(new PhotoUrl((string) $photoFileName));
        }

        $this->updateTransportUseCase->execute($request);
	}

    private function resolvePhoto(FileUpload $uploadedPhoto): ?string
    {
        if ($uploadedPhoto->getName() !== null) {
            return $this->photoService->saveTransportPhoto($uploadedPhoto);
        }

        return null;
	}
}
