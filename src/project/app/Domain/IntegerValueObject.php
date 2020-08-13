<?php
declare(strict_types=1);

namespace Domain\ValueObject;

class IntegerValueObject
{

    /**
     * @var int $value
     */
    public $value;

    public function __construct(int $value)
    {
        $this->value = $value;
    }

    public function equals(self $name): bool
    {
        return $this->value === $name->getValue();
    }

    public function getValue(): int
    {
        return $this->value;
    }
}
