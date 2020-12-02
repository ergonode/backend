<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Domain\User;

use Ergonode\SharedKernel\Domain\AggregateId;

interface AggregateUserInterface extends UserInterface
{
    public function getId(): AggregateId;
}
