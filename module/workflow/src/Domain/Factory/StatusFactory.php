<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Domain\Factory;

use Ergonode\Core\Domain\ValueObject\Color;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\Workflow\Domain\Entity\Status;
use Ergonode\Workflow\Domain\Entity\StatusId;

/**
 */
class StatusFactory
{
    /**
     * @param StatusId           $id
     * @param string             $code
     * @param Color              $color
     * @param TranslatableString $name
     * @param TranslatableString $description
     *
     * @return Status
     *
     * @throws \Exception
     */
    public function create(StatusId $id, string $code, Color $color, TranslatableString $name, TranslatableString $description): Status
    {
        return new Status(
            $id,
            $code,
            $color,
            $name,
            $description
        );
    }
}
