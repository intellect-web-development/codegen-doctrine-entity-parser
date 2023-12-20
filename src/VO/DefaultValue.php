<?php

declare(strict_types=1);

namespace IWD\CodeGen\CodegenDoctrineEntityParser\VO;

readonly class DefaultValue
{
    private bool $exists;
    private ?string $value;

    public static function notExists(): self
    {
        return new self(false, null);
    }

    public static function null(): self
    {
        return new self(true, null);
    }

    public static function fromValue(string $value): self
    {
        return new self(true, $value);
    }

    public function __construct(bool $exists, ?string $value)
    {
        $this->exists = $exists;
        $this->value = $value;
    }

    public function isExists(): bool
    {
        return $this->exists;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }
}
