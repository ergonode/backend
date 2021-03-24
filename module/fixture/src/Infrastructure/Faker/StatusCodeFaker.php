<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Fixture\Infrastructure\Faker;

use Ergonode\Workflow\Domain\ValueObject\StatusCode;
use Faker\Provider\Base as BaseProvider;

class StatusCodeFaker extends BaseProvider
{
    /**
     * @throws \Exception
     */
    public function statusCode(string $code): StatusCode
    {
        return new StatusCode($code);
    }
}
