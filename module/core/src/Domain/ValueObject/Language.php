<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Domain\ValueObject;

class Language
{
    private const PATTERN = '/^[a-z]{2}_[A-Z]{2}$/';

    private string $code;

    public function __construct(string $code)
    {
        $this->code = trim($code);
        if (!self::isValid($this->code)) {
            throw new \InvalidArgumentException(\sprintf('Code "%s" is not valid language code', $code));
        }
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function __toString(): string
    {
        return $this->getCode();
    }

    public function getLanguageCode(): string
    {
        return explode('_', $this->code)[0];
    }

    public function getTerritoryCode(): string
    {
        return explode('_', $this->code)[1];
    }

    /**
     * @return Language
     */
    public static function fromString(string $code): self
    {
        return new self($code);
    }

    public function isEqual(Language $language): bool
    {
        return $language->code === $this->code;
    }

    public static function isValid(string $code): bool
    {
        return preg_match(self::PATTERN, $code) === 1;
    }
}
