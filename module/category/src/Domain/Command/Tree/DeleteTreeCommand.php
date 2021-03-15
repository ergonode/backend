<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Category\Domain\Command\Tree;

use Ergonode\Category\Domain\Command\CategoryCommandInterface;
use Ergonode\SharedKernel\Domain\Aggregate\CategoryTreeId;
use JMS\Serializer\Annotation as JMS;

class DeleteTreeCommand implements CategoryCommandInterface
{
    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\CategoryTreeId")
     */
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
