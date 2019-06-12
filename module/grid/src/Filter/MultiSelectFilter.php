<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Grid\Filter;

use Ergonode\Grid\FilterInterface;

/**
 */
class MultiSelectFilter implements FilterInterface
{
    public const TYPE = 'MULTI_SELECT';

    /**
     * @var array;
     */
    private $options;

    /**
     * @var array
     */
    private $value;

    /**
     * @param array $options
     * @param array $value
     */
    public function __construct(array $options, array $value = [])
    {
        $this->options = $options;
        $this->value = $value;
    }

    /**
     * @return array
     */
    public function render(): array
    {
        return ['options' => $this->options];
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return self::TYPE;
    }

    /**
     * @return bool
     */
    public function isEqual(): bool
    {
        return false;
    }

    /**
     * @return string|array[]
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param array|string $value
     */
    public function setValue($value): void
    {
        $this->value = $value;
    }
}
