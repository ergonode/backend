<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Designer\Domain\Command;

use Ergonode\SharedKernel\Domain\Aggregate\TemplateId;

class DeleteTemplateCommand implements TemplateCommandInterface
{
    private TemplateId $id;

    public function __construct(TemplateId $id)
    {
        $this->id = $id;
    }

    public function getId(): TemplateId
    {
        return $this->id;
    }
}
