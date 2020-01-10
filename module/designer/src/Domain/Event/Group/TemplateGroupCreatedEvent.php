<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Designer\Domain\Event\Group;

use Ergonode\Core\Domain\Entity\AbstractId;
use Ergonode\Designer\Domain\Entity\TemplateGroupId;
use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use JMS\Serializer\Annotation as JMS;

/**
 */
class TemplateGroupCreatedEvent implements DomainEventInterface
{
    /**
     * @var TemplateGroupId
     *
     * @JMS\Type("Ergonode\Designer\Domain\Entity\TemplateGroupId")
     */
    private $id;
    /**
     * @var string
     *
     * @JMS\Type("string")
     */
    private $name;

    /**
     * @param TemplateGroupId $id
     * @param string          $name
     */
    public function __construct(TemplateGroupId $id, string $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

    /**
     * @return TemplateGroupId|AbstractId
     */
    public function getAggregateId(): AbstractId
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}
