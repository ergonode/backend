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

class MultimediaTitleChangedEvent implements AggregateEventInterface
{
    private MultimediaId $id;

    private TranslatableString $title;

    public function __construct(MultimediaId $id, TranslatableString $title)
    {
        $this->id = $id;
        $this->title = $title;
    }

    public function getAggregateId(): AggregateId
    {
        return $this->id;
    }

    public function getTitle(): TranslatableString
    {
        return $this->title;
    }
}
