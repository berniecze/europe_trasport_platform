<?php
declare(strict_types=1);

namespace Application\Transport\Request;

use Domain\Entity\Transport\ValueObject\PhotoUrl;

class RemoveTransportRequest
{

    /**
     * @var int
     */
    private $id;

    /**
     * @var PhotoUrl
     */
    private $photoUrl;

    public function __construct(int $id, PhotoUrl $photoUrl)
    {
        $this->id = $id;
        $this->photoUrl = $photoUrl;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getPhotoUrl(): PhotoUrl
    {
        return $this->photoUrl;
    }
}
