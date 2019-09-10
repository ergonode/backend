<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\CategoryTree\Domain\Command;

use Ergonode\CategoryTree\Domain\Entity\CategoryTreeId;
use JMS\Serializer\Annotation as JMS;

/**
 */
class DeleteTreeCommand
{
    /**
     * @var CategoryTreeId
     *
     * @JMS\Type("Ergonode\CategoryTree\Domain\Entity\CategoryTreeId")
     */
    private $id;

    /**
     * @param CategoryTreeId $id
     */
    public function __construct(CategoryTreeId $id)
    {
        $this->id = $id;
    }

    /**
     * @return CategoryTreeId
     */
    public function getId(): CategoryTreeId
    {
        return $this->id;
    }
}
