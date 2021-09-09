<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Domain\Command;

use Ergonode\SharedKernel\Domain\Aggregate\UnitId;

class UpdateUnitCommand implements CoreCommandInterface
{
    private UnitId $id;

    private string $name;

    private string $symbol;

    public function __construct(UnitId $id, string $name, string $symbol)
    {
        $this->id = $id;
        $this->name = $name;
        $this->symbol = $symbol;
    }

    public function getId(): UnitId
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getSymbol(): string
    {
        return $this->symbol;
    }
}
