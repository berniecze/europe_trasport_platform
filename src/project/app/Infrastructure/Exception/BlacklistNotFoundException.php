<?php
declare(strict_types=1);

namespace Infrastructure\Exception;

use Exception;

class BlacklistNotFoundException extends Exception
{
    public function __construct(int $transportId)
    {
        parent::__construct(
            sprintf(
                'Blacklist with id: %d not found',
                $transportId
            )
        );
    }
}
