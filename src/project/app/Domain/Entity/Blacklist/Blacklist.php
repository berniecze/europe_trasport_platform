<?php
declare(strict_types=1);

namespace Domain\Entity\Blacklist;

use Domain\Entity\Blacklist\ValueObject\FromDate;
use Domain\Entity\Blacklist\ValueObject\ToDate;
use Domain\Entity\Transport\Transport;

class Blacklist
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
        Transport $transport,
        FromDate $fromDate,
        ToDate $toDate
    ) {
      $this->transport = $transport;
      $this->fromDate = $fromDate;
      $this->toDate = $toDate;
    }

    public function setTransport(Transport $transport): void
    {
        $this->transport = $transport;
    }

    public function setFromDate(FromDate $fromDate): void
    {
        $this->fromDate = $fromDate;
    }

    public function setToDate(ToDate $toDate): void
    {
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
