<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Domain\Command\Group;

use Ergonode\SharedKernel\Domain\Aggregate\AttributeGroupId;
use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;

/**
 */
class DeleteAttributeGroupCommand implements DomainCommandInterface
{
    /**
     * @var AttributeGroupId
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\AttributeGroupId")
     */
    private AttributeGroupId $id;

    /**
     * @param AttributeGroupId $id
     */
    public function __construct(AttributeGroupId $id)
    {
        $this->id = $id;
    }

    /**
     * @return AttributeGroupId
     */
    public function getId(): AttributeGroupId
    {
        return $this->id;
    }
}
