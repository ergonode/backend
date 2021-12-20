<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Domain\Provider;

use Ergonode\Workflow\Domain\Query\StatusQueryInterface;

class StatusIdsProvider
{
    private StatusQueryInterface $query;

    public function __construct(StatusQueryInterface $query)
    {
        $this->query = $query;
    }
    /**
     * @return array
     */
    public function provide(): array
    {
        return $this->query->getAllStatusIds();
    }
}
