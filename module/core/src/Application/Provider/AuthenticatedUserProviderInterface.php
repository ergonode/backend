<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Core\Application\Provider;

use Ergonode\Account\Domain\Entity\User;

/**
 */
interface AuthenticatedUserProviderInterface
{
    /**
     * @return User
     */
    public function provide(): User;
}
