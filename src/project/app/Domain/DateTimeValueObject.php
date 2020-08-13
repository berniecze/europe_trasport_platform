<?php
declare(strict_types=1);

namespace Domain\ValueObject;

use DateTimeInterface;

class DateTimeValueObject
{

    /** @var DateTimeInterface $value */
    public $value;

    public function __construct(DateTimeInterface $value)
    {
        $this->value = $value;
    }

    public function equals(self $dateTimeValueObject): bool
    {
        return $this->value === $dateTimeValueObject->getValue();
    }

    public function getValue(): DateTimeInterface
    {
        return $this->value;
    }
}
