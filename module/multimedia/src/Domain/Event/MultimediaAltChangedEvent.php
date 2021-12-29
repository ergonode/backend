<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Multimedia\Domain\Event;

use Ergonode\SharedKernel\Domain\AggregateEventInterface;
use Ergonode\SharedKernel\Domain\Aggregate\MultimediaId;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\SharedKernel\Domain\AggregateId;

class MultimediaAltChangedEvent implements AggregateEventInterface
{
    private MultimediaId $id;

    private TranslatableString $alt;

    public function __construct(MultimediaId $id, TranslatableString $alt)
    {
        $this->id = $id;
        $this->alt = $alt;
    }

    public function getAggregateId(): AggregateId
    {
        return $this->id;
    }

    public function getAlt(): TranslatableString
    {
        return $this->alt;
    }
}
