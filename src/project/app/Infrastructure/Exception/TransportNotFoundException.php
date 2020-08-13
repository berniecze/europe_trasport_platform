<?php
declare(strict_types=1);

namespace Infrastructure\Exception;

use Exception;

class TransportNotFoundException extends Exception
{
    public function __construct(int $transportId)
    {
        parent::__construct(
            sprintf(
                'Transport with id: %d not found',
                $transportId
            )
        );
    }
}
