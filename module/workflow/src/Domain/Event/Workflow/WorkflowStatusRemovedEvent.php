<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Domain\Event\Workflow;

use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use Ergonode\Workflow\Domain\ValueObject\StatusCode;
use JMS\Serializer\Annotation as JMS;

/**
 */
class WorkflowStatusRemovedEvent implements DomainEventInterface
{
    /**
     * @var StatusCode
     *
     * @JMS\Type("Ergonode\Workflow\Domain\ValueObject\StatusCode")
     */
    private $code;

    /**
     * @param StatusCode $code
     */
    public function __construct(StatusCode $code)
    {
        $this->code = $code;
    }

    /**
     * @return StatusCode
     */
    public function getCode(): StatusCode
    {
        return $this->code;
    }
}
