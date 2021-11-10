<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Application\Form\Model\Option;

use Ergonode\Attribute\Application\Validator as AttributeAssert;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\SharedKernel\Domain\AggregateId;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @Assert\GroupSequence({"SimpleOptionModel", "class_constraint"})
 * @AttributeAssert\OptionCodeExists()
 * @AttributeAssert\AttributeHasOption(groups={"class_constraint"})
 */
class SimpleOptionModel extends OptionMoveModel
{
    /**
     * @Assert\NotBlank(message="Option code is required")
     * @Assert\Length(max=128, maxMessage="Option code is too long. It should contain {{ limit }} characters or less.")
     */
    public ?string $code;

    /**
     * @var array
     *
     * @Assert\All({
     *     @Assert\NotBlank(),
     *     @Assert\Length(
     *      max=255,
     *      maxMessage="Attribute name is too long. It should contain {{ limit }} characters or less."
     *     )
     * })
     */
    public array $label;

    public function __construct(AttributeId $attributeId, AggregateId $optionId = null)
    {
        parent::__construct($attributeId, $optionId);
        $this->code = null;
        $this->label = [];
    }
}
