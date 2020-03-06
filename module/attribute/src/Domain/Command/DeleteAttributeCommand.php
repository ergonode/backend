<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Domain\Command;

use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;

/**
 */
class DeleteAttributeCommand implements DomainCommandInterface
{
    /**
     * @var AttributeId
     */
    private AttributeId $id;

    /**
     * @param AttributeId $id
     */
    public function __construct(AttributeId $id)
    {
        $this->id = $id;
    }

    /**
     * @return AttributeId
     */
    public function getId(): AttributeId
    {
        return $this->id;
    }
}
