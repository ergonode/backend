<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Fixture\Infrastructure\Faker;

use Ergonode\Core\Domain\ValueObject\TranslatableString;

class TranslatableStringFaker
{
    /**
     * @param array $translations
     */
    public function translation(array $translations = []): TranslatableString
    {
        return new TranslatableString($translations);
    }
}
