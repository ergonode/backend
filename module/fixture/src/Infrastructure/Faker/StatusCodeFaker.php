<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Fixture\Infrastructure\Faker;

use Ergonode\Workflow\Domain\ValueObject\StatusCode;
use Faker\Provider\Base as BaseProvider;

/**
 */
class StatusCodeFaker extends BaseProvider
{
    /**
     * @param string $code
     *
     * @return StatusCode
     *
     * @throws \Exception
     */
    public function statusCode(string $code): StatusCode
    {
        return new StatusCode($code);
    }
}
