<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Category\Domain\Command;

use Ergonode\SharedKernel\Domain\Aggregate\CategoryId;
use Ergonode\Core\Domain\ValueObject\TranslatableString;

class UpdateCategoryCommand implements CategoryCommandInterface
{
    private CategoryId $id;

    private TranslatableString $name;

    public function __construct(CategoryId $id, TranslatableString $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

    public function getId(): CategoryId
    {
        return $this->id;
    }

    public function getName(): TranslatableString
    {
        return $this->name;
    }
}
