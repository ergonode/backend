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
class LabelFilter implements FilterInterface
{
    public const TYPE = 'SELECT';

    /**
     * @var array
     */
    private $values;

    /**
     * @var array;
     */
    private $options;

    /**
     * @param array $options
     * @param array $values
     */
    public function __construct(array $options, array $values = [])
    {
        $this->options = $options;
        $this->values = $values;
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
     * @return array[]
     */
    public function getValues(): array
    {
        return $this->values;
    }
}
