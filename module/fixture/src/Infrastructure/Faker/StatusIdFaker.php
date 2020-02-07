<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Fixture\Infrastructure\Faker;

use Ergonode\SharedKernel\Domain\Aggregate\StatusId;
use Ergonode\Workflow\Domain\ValueObject\StatusCode;
use Faker\Provider\Base as BaseProvider;

/**
 */
class StatusIdFaker extends BaseProvider
{
    /**
     * @param string|null $code
     *
     * @return StatusId
     *
     * @throws \Exception
     */
    public function statusId(string $code): StatusId
    {
        return StatusId::fromCode((new StatusCode($code))->getValue());
    }
}
