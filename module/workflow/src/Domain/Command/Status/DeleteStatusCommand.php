<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Domain\Command\Status;

use Ergonode\Workflow\Domain\Entity\WorkflowId;
use JMS\Serializer\Annotation as JMS;

/**
 */
class DeleteStatusCommand
{
    /**
     * @var WorkflowId
     *
     * @JMS\Type("Ergonode\Workflow\Domain\Entity\WorkflowId")
     */
    private $id;

    /**
     * @var string
     *
     * @JMS\Type("string")
     */
    private $code;

    /**
     * @param WorkflowId $id
     * @param string     $code
     */
    public function __construct(WorkflowId $id, string $code)
    {
        $this->id = $id;
        $this->code = $code;
    }

    /**
     * @return WorkflowId
     */
    public function getId(): WorkflowId
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }
}
