<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Category\Domain\Command;

use Ergonode\SharedKernel\Domain\Aggregate\CategoryId;

interface CreateCategoryCommandInterface extends CategoryCommandInterface
{
    public function getId(): CategoryId;
}
