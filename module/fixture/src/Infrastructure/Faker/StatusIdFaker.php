<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Fixture\Infrastructure\Faker;

use Ergonode\SharedKernel\Domain\Aggregate\StatusId;
use Faker\Provider\Base as BaseProvider;
use Ramsey\Uuid\Uuid;

/**
 */
class StatusIdFaker extends BaseProvider
{
    private const NAMESPACE = 'dcf14212-d63d-4829-b914-71e3d5599ad2';

    /**
     * @param string|null $code
     *
     * @return StatusId
     *
     * @throws \Exception
     */
    public function statusId(?string $code = null): StatusId
    {
        if ($code) {
            return new StatusId(Uuid::uuid5(self::NAMESPACE, $code)->toString());
        }

        return StatusId::generate();
    }
}
