<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Domain\Command\Status;

use Ergonode\Workflow\Domain\Entity\StatusId;
use JMS\Serializer\Annotation as JMS;
use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;

/**
 */
class DeleteStatusCommand implements DomainCommandInterface
{
    /**
     * @var StatusId
     *
     * @JMS\Type("Ergonode\Workflow\Domain\Entity\StatusId")
     */
    private $id;

    /**
     * @param StatusId $id
     */
    public function __construct(StatusId $id)
    {
        $this->id = $id;
    }

    /**
     * @return StatusId
     */
    public function getId(): StatusId
    {
        return $this->id;
    }
}
