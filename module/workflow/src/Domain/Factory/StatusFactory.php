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
use Ergonode\SharedKernel\Domain\Aggregate\StatusId;
use Ergonode\Workflow\Domain\ValueObject\StatusCode;

class StatusFactory
{
    /**
     * @throws \Exception
     */
    public function create(
        StatusCode $code,
        Color $color,
        TranslatableString $name,
        TranslatableString $description
    ): Status {
        return new Status(
            StatusId::fromCode($code->getValue()),
            $code,
            $color,
            $name,
            $description
        );
    }
}
