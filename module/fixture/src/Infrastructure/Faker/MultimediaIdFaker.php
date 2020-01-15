<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Fixture\Infrastructure\Faker;

use Ergonode\Multimedia\Domain\Entity\MultimediaId;
use Faker\Provider\Base as BaseProvider;

/**
 */
class MultimediaIdFaker extends BaseProvider
{
    /**
     * @param string|null $value
     *
     * @return MultimediaId
     *
     * @throws \Exception
     */
    public function multimediaId(?string $value = null): MultimediaId
    {
        if ($value) {
            return MultimediaId::fromKey($value);
        }

        return MultimediaId::generate();
    }
}
