<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Fixture\Infrastructure\Faker;

use Ergonode\SharedKernel\Domain\Aggregate\UserId;
use Faker\Provider\Base as BaseProvider;
use Ramsey\Uuid\Uuid;

/**
 */
class UserIdFaker extends BaseProvider
{
    private const NAMESPACE = 'eb5fa5eb-ecda-4ff6-ac91-9ac817062635';

    /**
     * @param string|null $email
     *
     * @return UserId
     *
     * @throws \Exception
     */
    public function userId(?string $email = null): UserId
    {
        if ($email) {
            return new UserId(Uuid::uuid5(self::NAMESPACE, $email)->toString());
        }

        return UserId::generate();
    }
}
