<?php
declare(strict_types=1);

namespace Domain\ValueObject;

class StringValueObject
{

    /**
     * @var string $value
     */
    public $value;

    public function __construct(string $value)
    {
        $this->value = $value;
    }

    public function equals(self $name): bool
    {
        return $this->value === $name->getValue();
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
