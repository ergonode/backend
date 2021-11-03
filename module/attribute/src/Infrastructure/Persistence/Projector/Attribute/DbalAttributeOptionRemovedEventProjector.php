<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Infrastructure\Persistence\Projector\Attribute;

use Ergonode\Attribute\Domain\Event\Attribute\AttributeOptionRemovedEvent;

class DbalAttributeOptionRemovedEventProjector extends AbstractDbalAttributeOptionEventProjector
{
    public function __invoke(AttributeOptionRemovedEvent $event): void
    {
        $position = $this->getPosition($event->getAggregateId(), $event->getOptionId());

        $this->connection->delete(
            self::TABLE,
            [
                'attribute_id' => $event->getAggregateId()->getValue(),
                'option_id' => $event->getOptionId()->getValue(),
            ]
        );

        $position--;

        $this->mergePosition($event->getAggregateId(), $position);
    }
}
