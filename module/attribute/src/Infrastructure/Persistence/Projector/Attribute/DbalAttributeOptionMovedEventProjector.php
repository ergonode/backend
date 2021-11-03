<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Infrastructure\Persistence\Projector\Attribute;

use Ergonode\Attribute\Domain\Event\Attribute\AttributeOptionAddedEvent;

class DbalAttributeOptionMovedEventProjector extends AbstractDbalAttributeOptionEventProjector
{
    public function __invoke(AttributeOptionAddedEvent $event): void
    {
        $option = $this->getPosition($event->getAggregateId(), $event->getOptionId());
        $index = $event->getIndex();

        $this->shiftPosition($event->getAggregateId(), $index);
        $this->mergePosition($event->getAggregateId(), $option);

        $this->connection->update(
            self::TABLE,
            [
                'index' => $index,
            ],
            [
                'attribute_id' => $event->getAggregateId()->getValue(),
                'option_id' => $event->getOptionId()->getValue(),
                'index' => $option,
            ]
        );
    }
}
