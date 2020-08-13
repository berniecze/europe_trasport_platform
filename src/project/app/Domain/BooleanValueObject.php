<?php
declare(strict_types=1);

namespace Domain\ValueObject;

class BooleanValueObject
{

    /**
     * @var bool $value
     */
    public $value;

    public function __construct(bool $value)
    {
        $this->value = $value;
    }

    public function equals(self $name): bool
    {
        return $this->value === $name->getValue();
    }

    public function getValue(): bool
    {
        return $this->value;
    }
}
