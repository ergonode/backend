<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Domain\Event\Workflow;

use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use Ergonode\Workflow\Domain\Entity\Transition;
use JMS\Serializer\Annotation as JMS;

/**
 */
class WorkflowTransitionAddedEvent implements DomainEventInterface
{
    /**
     * @var Transition
     *
     * @JMS\Type("Ergonode\Workflow\Domain\Entity\Transition")
     */
    private $transition;

    /**
     * @param Transition $transition
     */
    public function __construct(Transition $transition)
    {
        $this->transition = $transition;
    }

    /**
     * @return Transition
     */
    public function getTransition(): Transition
    {
        return $this->transition;
    }
}
