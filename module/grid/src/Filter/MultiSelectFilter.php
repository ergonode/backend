<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Grid\Filter;

use Ergonode\Grid\FilterInterface;
use Webmozart\Assert\Assert;
use Ergonode\Grid\Filter\Option\FilterOptionInterface;

class MultiSelectFilter implements FilterInterface
{
    public const TYPE = 'MULTI_SELECT';

    /**
     * @var FilterOptionInterface[]
     */
    private array $options;

    /**
     * @param array $options
     */
    public function __construct(array $options)
    {
        Assert::allIsInstanceOf($options, FilterOptionInterface::class);
        $this->options = $options;
    }

    /**
     * @return array
     */
    public function render(): array
    {
        $result['options'] = [];
        foreach ($this->options as $option) {
            $result['options'][$option->getKey()] = $option->render();
        }

        return $result;
    }

    public function getType(): string
    {
        return self::TYPE;
    }
}
