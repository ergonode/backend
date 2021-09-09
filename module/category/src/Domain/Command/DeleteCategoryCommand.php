<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Category\Domain\Command;

use Ergonode\SharedKernel\Domain\Aggregate\CategoryId;

class DeleteCategoryCommand implements CategoryCommandInterface
{
    private CategoryId $id;

    public function __construct(CategoryId $id)
    {
        $this->id = $id;
    }

    public function getId(): CategoryId
    {
        return $this->id;
    }
}
