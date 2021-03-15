<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Completeness\Infrastructure\Persistence\Projector\Template;

use Ergonode\Designer\Domain\Event\TemplateElementAddedEvent;
use Ergonode\Completeness\Infrastructure\Persistence\Projector\AbstractTemplateCompletenessProjector;

class TemplateElementAddedEventHandler extends AbstractTemplateCompletenessProjector
{
    public function __invoke(TemplateElementAddedEvent $event): void
    {
        $this->update($event->getAggregateId());
    }
}
