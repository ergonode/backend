<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Core\Domain\Command;

use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;
use Ergonode\SharedKernel\Domain\Aggregate\UnitId;
use JMS\Serializer\Annotation as JMS;

class UpdateUnitCommand implements DomainCommandInterface
{
    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\UnitId")
     */
    private UnitId $id;

    /**
     * @JMS\Type("string")
     */
    private string $name;

    /**
     * @JMS\Type("string")
     */
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
