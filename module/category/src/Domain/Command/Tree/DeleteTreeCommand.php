<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Category\Domain\Command\Tree;

use Ergonode\Category\Domain\Command\CategoryCommandInterface;
use Ergonode\SharedKernel\Domain\Aggregate\CategoryTreeId;

class DeleteTreeCommand implements CategoryCommandInterface
{
    private CategoryTreeId $id;

    public function __construct(CategoryTreeId $id)
    {
        $this->id = $id;
    }

    public function getId(): CategoryTreeId
    {
        return $this->id;
    }
}
