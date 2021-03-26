<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Application\Event;

use Ergonode\Attribute\Domain\Entity\AbstractAttribute;
use Ergonode\SharedKernel\Application\ApplicationEventInterface;

class AttributeDeletedEvent implements ApplicationEventInterface
{
    private AbstractAttribute $attribute;

    public function __construct(AbstractAttribute $attribute)
    {
        $this->attribute = $attribute;
    }

    public function getSegment(): AbstractAttribute
    {
        return $this->attribute;
    }
}
