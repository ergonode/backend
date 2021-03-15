<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Fixture\Infrastructure\Faker;

use Ergonode\Account\Domain\ValueObject\Privilege;
use Faker\Provider\Base as BaseProvider;

class PrivilegeFaker extends BaseProvider
{
    public function privilege(string $code): Privilege
    {
        return new Privilege($code);
    }
}
