<?php
declare(strict_types=1);

namespace Domain\ValueObject;

class FloatValueObject
{

    /**
     * @var float $value
     */
    public $value;

    public function __construct(float $value)
    {
        $this->value = $value;
    }

    public function equals(self $name): bool
    {
        return $this->value === $name->getValue();
    }

    public function getValue(): float
    {
        return (float) $this->value;
    }
}
