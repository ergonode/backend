<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\BatchAction\Domain\ValueObject;

use Webmozart\Assert\Assert;

class BatchActionMessage
{
    private string $message;

    /**
     * @var string[]
     */
    private array $properties;

    /**
     * @param string[] $properties
     */
    public function __construct(string $message, array $properties)
    {
        Assert::notEmpty($message);
        Assert::allStringNotEmpty($properties);
        Assert::allStringNotEmpty(array_keys($properties));

        $this->message = $message;
        $this->properties = $properties;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @return array
     */
    public function getProperties(): array
    {
        return $this->properties;
    }
}
