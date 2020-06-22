<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Persistence\Dbal\Projector\Attribute;

use Ergonode\Attribute\Domain\Event\Attribute\AbstractAttributeParameterChangeEvent;
use Ergonode\Attribute\Domain\Event\Attribute\AttributeBoolParameterChangeEvent;

/**
 */
class AttributeBoolParameterChangeEventProjector extends AbstractAttributeParameterChangeEventProjector
{
    /**
     * @param AttributeBoolParameterChangeEvent $event
     */
    public function __invoke(AttributeBoolParameterChangeEvent $event): void
    {
        $this->project($event);
    }
}
