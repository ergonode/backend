<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Designer\Domain\Query;

use Ergonode\SharedKernel\Domain\Aggregate\TemplateGroupId;

interface TemplateGroupQueryInterface
{
    /**
     * @return array
     */
    public function getDictionary(): array;

    public function getDefaultId(): TemplateGroupId;
}
