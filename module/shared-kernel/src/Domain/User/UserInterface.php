<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\SharedKernel\Domain\User;

use Ergonode\SharedKernel\Domain\Aggregate\UserId;

interface UserInterface
{
    public function getId(): UserId;
}
