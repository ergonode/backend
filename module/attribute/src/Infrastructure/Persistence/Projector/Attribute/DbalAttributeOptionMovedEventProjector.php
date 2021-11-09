<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Infrastructure\Persistence\Projector\Attribute;

use Ergonode\Attribute\Domain\Event\Attribute\AttributeOptionMovedEvent;

class DbalAttributeOptionMovedEventProjector extends AbstractDbalAttributeOptionEventProjector
{
    public function __invoke(AttributeOptionMovedEvent $event): void
    {
        $from = $this->getPosition($event->getAggregateId(), $event->getOptionId());
        $to = $event->getIndex();

        if ($from > $to) {
            $this->connection->delete(
                self::TABLE,
                [
                    'attribute_id' => $event->getAggregateId()->getValue(),
                    'option_id' => $event->getOptionId()->getValue(),
                    'index' => $from,
                ]
            );

            $this->mergePosition($event->getAggregateId(), $from);
            $this->shiftPosition($event->getAggregateId(), $to);

            $this->connection->insert(
                self::TABLE,
                [
                    'attribute_id' => $event->getAggregateId()->getValue(),
                    'option_id' => $event->getOptionId()->getValue(),
                    'index' => $to,
                ]
            );
        } else {
            $this->shiftPosition($event->getAggregateId(), $to);

            $this->connection->delete(
                self::TABLE,
                [
                    'attribute_id' => $event->getAggregateId()->getValue(),
                    'option_id' => $event->getOptionId()->getValue(),
                    'index' => $from,
                ]
            );

            $this->connection->insert(
                self::TABLE,
                [
                    'attribute_id' => $event->getAggregateId()->getValue(),
                    'option_id' => $event->getOptionId()->getValue(),
                    'index' => $to,
                ]
            );

            $this->mergePosition($event->getAggregateId(), $from);
        }
    }
}
