<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Designer\Domain\Entity;

use Ergonode\Core\Domain\Entity\AbstractId;
use Ergonode\Designer\Domain\Event\Group\TemplateGroupCreatedEvent;
use Ergonode\EventSourcing\Domain\AbstractAggregateRoot;

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
     *
     * @throws \Exception
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
        $this->id = $event->getAggregateId();
        $this->name = $event->getName();
    }
}
