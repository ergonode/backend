<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Application\Form\Model;

use Doctrine\Common\Collections\ArrayCollection;
use Ergonode\Attribute\Domain\ValueObject\AttributeCode;
use Ergonode\Attribute\Domain\ValueObject\AttributeType;
use Symfony\Component\Validator\Constraints as Assert;
use Ergonode\Attribute\Infrastructure\Validator as AppAssert;

/**
 */
class CreateAttributeFormModel
{
    /**
     * @var AttributeCode
     *
     * @Assert\NotBlank(message="Attribute code is required")
     * @Assert\Length(max=128)
     * @AppAssert\AttributeCode()
     */
    public $code;

    /**
     * @var AttributeType
     *
     * @Assert\NotBlank(message="Attribute type is required")
     */
    public $type;

    /**
     * @var bool
     */
    public $multilingual;

    /**
     * @var array
     *
     * @Assert\All({
     *     @Assert\NotBlank(),
     *     @Assert\Length(max=32, maxMessage="Attribute name is to long, It should have {{ limit }} character or less.")
     * })
     */
    public $label;

    /**
     * @var array
     *
     * @Assert\All({
     *     @Assert\NotBlank(),
     *     @Assert\Length(max=4000, maxMessage="Attribute placeholder is to long, It should have {{ limit }} character or less.")
     * })
     */
    public $placeholder;

    /**
     * @var array
     *
     * @Assert\All({
     *     @Assert\NotBlank(),
     *     @Assert\Length(max=4000, maxMessage="Attribute hint is to long,. It should have {{ limit }} character or less.")
     * })
     */
    public $hint;

    /**
     * @var array
     */
    public $groups;

    /**
     * @var AttributeParametersModel
     */
    public $parameters;

    /**
     * @var ArrayCollection|AttributeOptionModel[]
     */
    public $options;

    /**
     * AttributeFormModel constructor.
     */
    public function __construct()
    {
        $this->label = [];
        $this->placeholder = [];
        $this->hint = [];
        $this->groups = [];
        $this->parameters = new AttributeParametersModel();
        $this->options = new ArrayCollection();
    }
}
