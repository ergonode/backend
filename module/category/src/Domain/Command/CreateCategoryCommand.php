<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Category\Domain\Command;

use Ergonode\SharedKernel\Domain\Aggregate\CategoryId;
use Ergonode\Category\Domain\ValueObject\CategoryCode;
use Ergonode\Core\Domain\ValueObject\TranslatableString;

class CreateCategoryCommand implements CreateCategoryCommandInterface
{
    private CategoryId $id;

    private TranslatableString $name;

    private CategoryCode $code;

    /**
     * @throws \Exception
     */
    public function __construct(CategoryId $id, CategoryCode $code, TranslatableString $name)
    {
        $this->id = $id;
        $this->code = $code;
        $this->name = $name;
    }

    public function getId(): CategoryId
    {
        return $this->id;
    }

    public function getCode(): CategoryCode
    {
        return $this->code;
    }

    public function getName(): TranslatableString
    {
        return $this->name;
    }
}
