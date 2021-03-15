<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\SharedKernel\Domain\Collection;

use Ergonode\SharedKernel\Domain\ValueObject\Email;
use Ramsey\Collection\AbstractCollection;

class EmailCollection extends AbstractCollection
{
    public function getType(): string
    {
        return Email::class;
    }

    /**
     * @return array
     */
    public function asStringArray(): array
    {
        return array_map(fn($item) => (string) $item, $this->data);
    }
}
