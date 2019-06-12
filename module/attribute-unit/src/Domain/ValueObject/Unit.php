<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\AttributeUnit\Domain\ValueObject;

/**
 */
class Unit
{
    /**
     * @var string
     */
    private $code;

    /**
     * @param string $code
     */
    public function __construct(string $code)
    {
        $this->code = strtoupper(trim($code));
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
     * @return Unit
     */
    public static function fromString(string $code): self
    {
        return new self($code);
    }
}
