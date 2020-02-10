<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Segment\Domain\Command;

use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;
use Ergonode\SharedKernel\Domain\Aggregate\SegmentId;

/**
 */
class DeleteSegmentCommand implements DomainCommandInterface
{
    /**
     * @var SegmentId
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\SegmentId")
     */
    private SegmentId $id;

    /**
     * @param SegmentId $id
     */
    public function __construct(SegmentId $id)
    {
        $this->id = $id;
    }

    /**
     * @return SegmentId
     */
    public function getId(): SegmentId
    {
        return $this->id;
    }
}
