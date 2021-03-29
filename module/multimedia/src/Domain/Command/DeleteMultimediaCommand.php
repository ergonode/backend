<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Multimedia\Domain\Command;

use Ergonode\SharedKernel\Domain\Aggregate\MultimediaId;

class DeleteMultimediaCommand implements MultimediaCommandInterface
{
    private MultimediaId $id;

    public function __construct(MultimediaId $id)
    {
        $this->id = $id;
    }

    public function getId(): MultimediaId
    {
        return $this->id;
    }
}
