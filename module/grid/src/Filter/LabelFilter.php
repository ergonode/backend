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
    private array $options;

    /**
     * @param array $options
     */
    public function __construct(array $options)
    {
        $this->options = $options;
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
}
