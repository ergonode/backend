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

/**
 */
class UpdateUnitCommand implements DomainCommandInterface
{
    /**
     * @var UnitId
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\UnitId")
     */
    private UnitId $id;

    /**
     * @var string $name
     *
     * @JMS\Type("string")
     */
    private string $name;

    /**
     * @var string $symbol
     *
     * @JMS\Type("string")
     */
    private string $symbol;

    /**
     * @param UnitId $id
     * @param string $name
     * @param string $symbol
     */
    public function __construct(UnitId $id, string $name, string $symbol)
    {
        $this->id = $id;
        $this->name = $name;
        $this->symbol = $symbol;
    }

    /**
     * @return UnitId
     */
    public function getId(): UnitId
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getSymbol(): string
    {
        return $this->symbol;
    }
}
