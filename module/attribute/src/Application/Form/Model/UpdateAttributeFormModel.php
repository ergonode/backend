<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Application\Form\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Ergonode\Attribute\Domain\ValueObject\AttributeType;
use Ergonode\Attribute\Infrastructure\Validator as AppAssert;
use Symfony\Component\Validator\Constraints as Assert;

/**
 */
class UpdateAttributeFormModel
{
    /**
     * @var AttributeType
     */
    public $type;

    /**
     * @var array
     */
    public $groups;

    /**
     * @var array
     *
     * @Assert\All({
     *     @Assert\NotBlank(),
     *     @Assert\Length(max=32, maxMessage="Attribute name is to long. It should have {{ limit }} character or less.")
     * })
     */
    public $label;

    /**
     * @var array
     *
     * @Assert\All({
     *     @Assert\NotBlank(),
     *     @Assert\Length(max=4000, maxMessage="Attribute placeholder is to long. It should have {{ limit }} character or less.")
     * })
     */
    public $placeholder;

    /**
     * @var array
     *
     * @Assert\All({
     *     @Assert\NotBlank(),
     *     @Assert\Length(max=4000, maxMessage="Attribute hint is to long. It should have {{ limit }} character or less.")
     * })
     */
    public $hint;

    /**
     * @var AttributeParametersModel
     */
    public $parameters;

    /**
     * @var ArrayCollection|AttributeOptionModel[]
     *
     * @Assert\Valid()
     * @AppAssert\AttributeOptionDuplicates()
     */
    public $options;

    /**
     * @param AttributeType $type
     */
    public function __construct(AttributeType $type)
    {
        $this->label = [];
        $this->placeholder = [];
        $this->hint = [];
        $this->type = $type;
        $this->groups = [];
        $this->parameters = new AttributeParametersModel();
        $this->options = new ArrayCollection();
    }
}
