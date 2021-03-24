<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Fixture\Infrastructure\Faker;

use Faker\Provider\Base as BaseProvider;
use Ergonode\Value\Domain\ValueObject\TranslatableStringValue;
use Ergonode\Core\Domain\ValueObject\TranslatableString;

class TranslatableStringValueFaker extends BaseProvider
{
    public function translatableStringValue(TranslatableString $values): TranslatableStringValue
    {
        return new TranslatableStringValue($values);
    }
}
