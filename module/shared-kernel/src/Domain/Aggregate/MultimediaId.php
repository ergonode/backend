<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 *
 */

declare(strict_types = 1);

namespace Ergonode\SharedKernel\Domain\Aggregate;

use Ergonode\SharedKernel\Domain\AggregateId;

/**
 */
class MultimediaId extends AggregateId
{
    public const NAMESPACE = '690c9b97-57bc-4c71-9b62-37093c578836';

    /**
     * @param string $value
     *
     * @return MultimediaId
     */
    public static function fromKey(string $value):MultimediaId
    {
        return new static(self::generateIdentifier(self::NAMESPACE, $value)->getValue());
    }
}
