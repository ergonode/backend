<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Designer\Domain\Entity;

use Ergonode\EventSourcing\Domain\AbstractAggregateRoot;
use Ergonode\Core\Domain\Entity\AbstractId;
use Ergonode\Designer\Domain\Event\Group\TemplateGroupCreatedEvent;

/**
 */
class TemplateGroup extends AbstractAggregateRoot
{
    /**
     * @var TemplateGroupId
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @param TemplateGroupId $id
     * @param string          $name
     */
    public function __construct(TemplateGroupId $id, string $name)
    {
        $this->apply(new TemplateGroupCreatedEvent($id, $name));
    }

    /**
     * @return TemplateGroupId
     */
    public function getId(): AbstractId
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

    /**
     * @param TemplateGroupCreatedEvent $event
     */
    protected function applyTemplateGroupCreatedEvent(TemplateGroupCreatedEvent $event): void
    {
        $this->id = $event->getId();
        $this->name = $event->getName();
    }
}
