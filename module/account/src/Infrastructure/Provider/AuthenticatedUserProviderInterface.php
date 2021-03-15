<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Infrastructure\Provider;

use Ergonode\Account\Domain\Entity\User;

interface AuthenticatedUserProviderInterface
{
    public function provide(): User;
}
