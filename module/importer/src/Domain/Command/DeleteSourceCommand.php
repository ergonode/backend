<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Domain\Command;

use Ergonode\SharedKernel\Domain\Aggregate\SourceId;

class DeleteSourceCommand implements ImporterCommandInterface
{
    private SourceId $id;

    public function __construct(SourceId $id)
    {
        $this->id = $id;
    }

    public function getId(): SourceId
    {
        return $this->id;
    }
}
