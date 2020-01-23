<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 *  See LICENSE.txt for license details.
 *
 */

declare(strict_types = 1);

namespace Ergonode\Exporter\Domain\Entity;

use JMS\Serializer\Annotation as JMS;

/**
 */
class Attribute
{
    /**
     * @var string
     *
     * @JMS\Type("string")
     */
    protected string $key;

    /**
     * @var string
     *
     * @JMS\Type("string")
     */
    protected string  $value;

    /**
     * AbstractAttribute constructor.
     * @param string $key
     * @param string $value
     */
    public function __construct(string $key, string $value)
    {
        $this->key = $key;
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }
}
