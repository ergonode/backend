<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Domain\Entity;

use Ergonode\SharedKernel\Domain\Aggregate\WorkflowId;

/**
 */
interface WorkflowInterface
{
    /**
     * @return string
     */
    public static function getType(): string;

    /**
     * @return string
     */
    public function getCode(): string;

    /**
     * @return WorkflowId;
     */
    public function getId(): WorkflowId;
}
