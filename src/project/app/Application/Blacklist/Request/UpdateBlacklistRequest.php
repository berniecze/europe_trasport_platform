<?php
declare(strict_types=1);

namespace Application\Blacklist\Request;

use Domain\Entity\Blacklist\ValueObject\FromDate;
use Domain\Entity\Blacklist\ValueObject\ToDate;
use Domain\Entity\Transport\Transport;

class UpdateBlacklistRequest
{

    /**
     * @var int
     */
    private $id;

    /**
     * @var Transport
     */
    private $transport;

    /**
     * @var FromDate
     */
    private $fromDate;

    /**
     * @var ToDate
     */
    private $toDate;

    public function __construct(
        int $id,
        Transport $transport,
        FromDate $fromDate,
        ToDate $toDate
    ) {
        $this->id = $id;
        $this->transport = $transport;
        $this->fromDate = $fromDate;
        $this->toDate = $toDate;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTransport(): Transport
    {
        return $this->transport;
    }

    public function getFromDate(): FromDate
    {
        return $this->fromDate;
    }

    public function getToDate(): ToDate
    {
        return $this->toDate;
    }
}
