<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Category\Domain\Event\Tree;

use Ergonode\SharedKernel\Domain\Aggregate\CategoryTreeId;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\EventSourcing\Domain\Event\AbstractTranslatableStringBasedChangedEvent;
use JMS\Serializer\Annotation as JMS;

class CategoryTreeNameChangedEvent extends AbstractTranslatableStringBasedChangedEvent
{
    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\CategoryTreeId")
     */
    private CategoryTreeId $id;

    public function __construct(CategoryTreeId $id, TranslatableString $from, TranslatableString $to)
    {
        parent::__construct($from, $to);

        $this->id = $id;
    }

    public function getAggregateId(): CategoryTreeId
    {
        return $this->id;
    }
}
