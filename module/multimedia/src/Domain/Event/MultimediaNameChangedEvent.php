<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Multimedia\Domain\Event;

use Ergonode\SharedKernel\Domain\AggregateEventInterface;
use Ergonode\SharedKernel\Domain\Aggregate\MultimediaId;
use Ergonode\SharedKernel\Domain\AggregateId;
use JMS\Serializer\Annotation as JMS;

class MultimediaNameChangedEvent implements AggregateEventInterface
{
    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\MultimediaId")
     */
    private MultimediaId $id;

    /**
     * @JMS\Type("string")
     */
    private string $name;

    public function __construct(MultimediaId $id, string $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

    public function getAggregateId(): AggregateId
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
