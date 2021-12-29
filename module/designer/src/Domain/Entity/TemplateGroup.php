<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Designer\Domain\Entity;

use Ergonode\Designer\Domain\Event\Group\TemplateGroupCreatedEvent;
use Ergonode\EventSourcing\Domain\AbstractAggregateRoot;
use Ergonode\SharedKernel\Domain\Aggregate\TemplateGroupId;

class TemplateGroup extends AbstractAggregateRoot
{
    private TemplateGroupId $id;

    private string $name;

    /**
     * @throws \Exception
     */
    public function __construct(TemplateGroupId $id, string $name)
    {
        $this->apply(new TemplateGroupCreatedEvent($id, $name));
    }

    public function getId(): TemplateGroupId
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    protected function applyTemplateGroupCreatedEvent(TemplateGroupCreatedEvent $event): void
    {
        $this->id = $event->getAggregateId();
        $this->name = $event->getName();
    }
}
