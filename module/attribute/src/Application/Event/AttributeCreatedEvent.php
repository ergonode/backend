<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Application\Event;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\SharedKernel\Application\ApplicationEventInterface;

class AttributeCreatedEvent implements ApplicationEventInterface
{
    private AbstractAttribute $attribute;

    public function __construct(AbstractAttribute $attribute)
    {
        $this->attribute = $attribute;
    }

    public function getAttribute(): AbstractAttribute
    {
        return $this->attribute;
    }
}
