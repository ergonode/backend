<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Grid\Filter;

use Ergonode\Grid\FilterInterface;

/**
 */
class SelectFilter implements FilterInterface
{
    public const TYPE = 'SELECT';

    /**
     * @var string
     */
    private $value;

    /**
     * @var array;
     */
    private $options;

    /**
     * @param array       $options
     * @param string|null $value
     */
    public function __construct(array $options, ?string $value = null)
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
        return true;
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
