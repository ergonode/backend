<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Application\Form\Model\Option;

use Ergonode\Attribute\Infrastructure\Validator\OptionCodeExists;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\SharedKernel\Domain\AggregateId;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @OptionCodeExists()
 */
class SimpleOptionModel
{
    /**
     * @var AttributeId|null
     */
    public ?AttributeId $attributeId;

    /**
     * @var AggregateId|null
     */
    public ?AggregateId $optionId;

    /**
     * @var string
     *
     * @Assert\NotBlank(message="Option code is required")
     * @Assert\Length(max=128, maxMessage="Option code is to long. It should have {{ limit }} character or less.")
     */
    public ?string $code;

    /**
     * @var array
     *
     * @Assert\All({
     *     @Assert\NotBlank(),
     *     @Assert\Length(
     *      max=255,
     *      maxMessage="Attribute name is to long, It should have {{ limit }} character or less."
     *     )
     * })
     */
    public array $label;

    /**
     * @param AttributeId $attributeId
     * @param AggregateId $optionId
     */
    public function __construct(AttributeId $attributeId = null, AggregateId $optionId = null)
    {
        $this->attributeId = $attributeId;
        $this->optionId = $optionId;
        $this->code = null;
        $this->label = [];
    }
}
