<?php
declare(strict_types=1);

namespace Application\Transport\Request;

use Domain\Entity\Transport\ValueObject\Active;
use Domain\Entity\Transport\ValueObject\Capacity;
use Domain\Entity\Transport\ValueObject\Description;
use Domain\Entity\Transport\ValueObject\FixedPrice;
use Domain\Entity\Transport\ValueObject\Luggage;
use Domain\Entity\Transport\ValueObject\MultiplierPrice;
use Domain\Entity\Transport\ValueObject\Name;
use Domain\Entity\Transport\ValueObject\PhotoUrl;

class UpdateTransportRequest
{

    /**
     * @var int
     */
    private $id;

    /**
     * @var Active
     */
    private $active;

    /**
     * @var Name
     */
    private $name;

    /**
     * @var PhotoUrl|null
     */
    private $photoUrl;

    /**
     * @var Capacity
     */
    private $capacity;

    /**
     * @var Luggage
     */
    private $luggage;

    /**
     * @var FixedPrice
     */
    private $fixedPrice;

    /**
     * @var MultiplierPrice
     */
    private $multiplierPrice;

    /**
     * @var Description|null
     */
    private $description;

    public function __construct(
        int $id,
        Active $active,
        Name $name,
        Capacity $capacity,
        Luggage $luggage,
        FixedPrice $fixedPrice,
        MultiplierPrice $multiplierPrice
    )
    {
        $this->id = $id;
        $this->active = $active;
        $this->name = $name;
        $this->capacity = $capacity;
        $this->luggage = $luggage;
        $this->fixedPrice = $fixedPrice;
        $this->multiplierPrice = $multiplierPrice;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getActive(): Active
    {
        return $this->active;
    }

    public function getName(): Name
    {
        return $this->name;
    }

    public function getPhotoUrl(): ?PhotoUrl
    {
        return $this->photoUrl;
    }

    public function setPhotoUrl(?PhotoUrl $photoUrl): void
    {
        $this->photoUrl = $photoUrl;
    }

    public function getCapacity(): Capacity
    {
        return $this->capacity;
    }

    public function getLuggage(): Luggage
    {
        return $this->luggage;
    }

    public function getFixedPrice(): FixedPrice
    {
        return $this->fixedPrice;
    }

    public function getMultiplierPrice(): MultiplierPrice
    {
        return $this->multiplierPrice;
    }

    public function getDescription(): ?Description
    {
        return $this->description;
    }

    public function setDescription(?Description $description): void
    {
        $this->description = $description;
    }
}
