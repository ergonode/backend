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
class DeleteUnitCommand implements DomainCommandInterface
{
    /**
     * @var UnitId
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\UnitId")
     */
    private UnitId $id;

    /**
     * @param UnitId $id
     */
    public function __construct(UnitId $id)
    {
        $this->id = $id;
    }

    /**
     * @return UnitId
     */
    public function getId(): UnitId
    {
        return $this->id;
    }
}
