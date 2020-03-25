<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Fixture\Infrastructure\Faker;

use Ergonode\SharedKernel\Domain\Aggregate\MultimediaId;
use Faker\Provider\Base as BaseProvider;
use Ramsey\Uuid\Uuid;

/**
 */
class MultimediaIdFaker extends BaseProvider
{
    private const NAMESPACE = '690c9b97-57bc-4c71-9b62-37093c578836';

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
            return new MultimediaId(Uuid::uuid5(self::NAMESPACE, $value)->toString());
        }

        return MultimediaId::generate();
    }
}
