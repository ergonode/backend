<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Infrastructure\Persistence\Projector\Attribute;

use Ergonode\Attribute\Domain\Event\Attribute\AttributeStringParameterChangeEvent;

class DbalAttributeStringParameterChangeEventProjector extends AbstractDbalAttributeParameterChangeEventProjector
{
    public function __invoke(AttributeStringParameterChangeEvent $event): void
    {
        $this->projection($event->getAggregateId(), $event->getName(), $event->getTo());
    }
}
