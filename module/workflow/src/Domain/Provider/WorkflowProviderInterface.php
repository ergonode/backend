<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Domain\Provider;

use Ergonode\Workflow\Domain\Entity\AbstractWorkflow;
use Ergonode\Core\Domain\ValueObject\Language;

interface WorkflowProviderInterface
{
    public function provide(?Language $language = null): AbstractWorkflow;
}
