<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Completeness\Infrastructure\Handler\Event\Application;

use Ergonode\Completeness\Infrastructure\Persistence\Manager\CompletenessManager;
use Ergonode\Designer\Application\Event\TemplateUpdatedEvent;

class TemplateUpdateEventHandler
{
    private CompletenessManager $manager;

    public function __construct(CompletenessManager $manager)
    {
        $this->manager = $manager;
    }

    public function __invoke(TemplateUpdatedEvent $event): void
    {
         $this->manager->recalculateTemplate($event->getTemplate()->getId());
    }
}
