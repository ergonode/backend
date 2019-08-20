<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Domain\ValueObject;

use Egulias\EmailValidator\EmailValidator;
use Egulias\EmailValidator\Validation\RFCValidation;
use Ergonode\Account\Domain\Exception\InvalidEmailException;

/**
 */
class Email
{
    /**
     * @var string
     */
    private $value;

    /**
     * @param string $value
     */
    public function __construct(string $value)
    {
        if (!self::isValid($value)) {
            throw new InvalidEmailException('Value is not correct email');
        }

        $this->value = mb_strtolower(trim($value));
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->value;
    }

    /**
     * @param Email $value
     *
     * @return bool
     */
    public function isEqual(Email $value): bool
    {
        return $value->getValue() === $this->value;
    }

    /**
     * @param string $value
     *
     * @return bool
     */
    public static function isValid(string $value): bool
    {
        $value = mb_strtolower(trim($value));

        return (new EmailValidator())->isValid($value, new RFCValidation());
    }
}
