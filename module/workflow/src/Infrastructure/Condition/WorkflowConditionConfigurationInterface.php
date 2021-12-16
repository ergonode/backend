<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Infrastructure\Condition;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Workflow\Infrastructure\Condition\Configuration\WorkflowConditionConfiguration;

interface WorkflowConditionConfigurationInterface
{
    public function supports(string $type): bool;

    public function getConfiguration(Language $language): WorkflowConditionConfiguration;
}
