<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Infrastructure\Provider;

use Ergonode\Attribute\Domain\ValueObject\DateFormat;

class DateFormatProvider
{
    /**
     * @return array
     */
    public function dictionary(): array
    {
        $result = [];
        foreach (DateFormat::DICTIONARY as $label => $id) {
            $result[$id] = $label;
        }

        return $result;
    }
}
