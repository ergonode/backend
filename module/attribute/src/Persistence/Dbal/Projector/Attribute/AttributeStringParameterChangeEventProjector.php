<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Persistence\Dbal\Projector\Attribute;

use Ergonode\Attribute\Domain\Event\Attribute\AttributeStringParameterChangeEvent;

/**
 */
class AttributeStringParameterChangeEventProjector extends AbstractAttributeParameterChangeEventProjector
{
    /**
     * @param AttributeStringParameterChangeEvent $event
     */
    public function __invoke(AttributeStringParameterChangeEvent $event)
    {
        $this->project($event);
    }
}
