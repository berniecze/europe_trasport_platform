<?php
declare(strict_types=1);

namespace Application\Blacklist\Request;

use Domain\Entity\Blacklist\ValueObject\FromDate;
use Domain\Entity\Blacklist\ValueObject\ToDate;
use Domain\Entity\Transport\Transport;

class CreateBlacklistRequest
{

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
        Transport $transport,
        FromDate $fromDate,
        ToDate $toDate
    ) {
        $this->transport = $transport;
        $this->fromDate = $fromDate;
        $this->toDate = $toDate;
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
