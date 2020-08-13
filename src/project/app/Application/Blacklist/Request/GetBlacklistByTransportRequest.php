<?php
declare(strict_types=1);

namespace Application\Blacklist\Request;

use Domain\Entity\Transport\Transport;

class GetBlacklistByTransportRequest
{

    /**
     * @var Transport $transport
     */
    private $transport;

    public function __construct(Transport $transport)
    {
        $this->transport = $transport;
    }

    public function getTransport(): Transport
    {
        return $this->transport;
    }
}
