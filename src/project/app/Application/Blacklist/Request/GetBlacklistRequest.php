<?php
declare(strict_types=1);

namespace Application\Blacklist\Request;

use Domain\Entity\Blacklist\ValueObject\FromDate;
use Domain\Entity\Blacklist\ValueObject\ToDate;
use Domain\Entity\Transport\Transport;

class GetBlacklistRequest
{

    /**
     * @var int
     */
    private $id;

    public function __construct(int $id)
    {
        $this->id = $id;
    }

    public function getId(): int
    {
        return $this->id;
    }
}
