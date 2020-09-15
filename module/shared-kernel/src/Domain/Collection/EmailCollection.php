<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\SharedKernel\Domain\Collection;

use Ergonode\SharedKernel\Domain\ValueObject\Email;

/**
 * @todo Waiting for decision about collection library
 */
class EmailCollection
{
    /**
     * @var array
     */
    private array $data;

    /**
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        $this->data = $data;
    }

    /**
     * @param Email $item
     */
    public function add(Email $item): void
    {
        $this->data[] = $item;
    }

    /**
     * @return bool
     */
    public function isEmpty(): bool
    {
        return empty($this->data);
    }

    /**
     * @return array
     */
    public function asStringArray(): array
    {
        $array = $this->data;
        array_walk($array, function (&$item) {
            $item = (string) $item;
        });

        return $array;
    }
}
