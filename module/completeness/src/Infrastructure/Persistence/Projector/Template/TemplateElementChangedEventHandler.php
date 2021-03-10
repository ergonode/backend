<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Completeness\Infrastructure\Persistence\Projector\Template;

use Ergonode\Designer\Domain\Event\TemplateElementChangedEvent;
use Ergonode\Completeness\Infrastructure\Persistence\Projector\AbstractTemplateCompletenessProjector;

class TemplateElementChangedEventHandler extends AbstractTemplateCompletenessProjector
{
    public function __invoke(TemplateElementChangedEvent $event): void
    {
        $this->update($event->getAggregateId());
    }
}
