<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Core\Domain\ValueObject;

/**
 */
class Language
{
    private const PATTERN = '/^[a-z]{2}(?:_[A-Z]{2}){0,2}$/';

    /**
     * @var string
     */
    private string $code;

    /**
     * @param string $code
     */
    public function __construct(string $code)
    {
        $this->code = trim($code);
        if (!self::isValid($this->code)) {
            throw new \InvalidArgumentException(\sprintf('Code "%s" is not valid language code', $code));
        }
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->getCode();
    }


    /**
     * @param string $code
     *
     * @return Language
     */
    public static function fromString(string $code): self
    {
        return new self($code);
    }

    /**
     * @param Language $language
     *
     * @return bool
     */
    public function isEqual(Language $language): bool
    {
        return $language->code === $this->code;
    }

    /**
     * @param string $code
     *
     * @return bool
     */
    public static function isValid(?string $code): bool
    {
        return preg_match(self::PATTERN, $code) === 1;
    }
}
