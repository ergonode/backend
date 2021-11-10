<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Application\Form\Model\Option;

use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\SharedKernel\Domain\AggregateId;
use Ergonode\Attribute\Application\Validator as AttributeAssert;
use Symfony\Component\Validator\Constraints as Assert;

class OptionMoveModel
{
    public ?AttributeId $attributeId;

    public ?AggregateId $optionId;

    public bool $after = true;

    /**
     * @Assert\Uuid(strict=true)
     * @AttributeAssert\OptionExists()
     */
    public ?string $positionId;

    public function __construct(AttributeId $attributeId = null, AggregateId $optionId = null)
    {
        $this->attributeId = $attributeId;
        $this->optionId = $optionId;
        $this->after = true;
        $this->positionId = null;
    }
}
