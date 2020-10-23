<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Category\Domain\Command;

use Ergonode\SharedKernel\Domain\Aggregate\CategoryId;
use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;
use JMS\Serializer\Annotation as JMS;

class DeleteCategoryCommand implements DomainCommandInterface
{
    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\CategoryId")
     */
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
