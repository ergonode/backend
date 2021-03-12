<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Completeness\Infrastructure\Persistence\Projector\Template;

use Ergonode\Designer\Domain\Event\TemplateElementRemovedEvent;
use Ergonode\Completeness\Infrastructure\Persistence\Projector\AbstractTemplateCompletenessProjector;

class TemplateElementRemovedEventHandler extends AbstractTemplateCompletenessProjector
{
    public function __invoke(TemplateElementRemovedEvent $event): void
    {
        $this->update($event->getAggregateId());
    }
}
