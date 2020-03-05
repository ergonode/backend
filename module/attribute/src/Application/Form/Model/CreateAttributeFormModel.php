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
use Ergonode\Attribute\Infrastructure\Validator as AppAssert;
use Symfony\Component\Validator\Constraints as Assert;

/**
 */
class CreateAttributeFormModel
{
    /**
     * @var AttributeCode
     *
     * @Assert\NotBlank(message="Attribute code is required")
     * @Assert\Length(max=128)
     *
     * @AppAssert\AttributeCode()
     */
    public ?AttributeCode $code;

    /**
     * @var AttributeType
     *
     * @Assert\NotBlank(message="Attribute type is required")
     */
    public ?AttributeType $type;

    /**
     * @var bool
     */
    public bool $multilingual;

    /**
     * @var array
     *
     * @Assert\All({
     *     @Assert\NotBlank(),
     *     @Assert\Length(
     *      max=32,
     *      maxMessage="Attribute name is to long, It should have {{ limit }} character or less."
     *     )
     * })
     */
    public array $label;

    /**
     * @var array
     *
     * @Assert\All({
     *     @Assert\NotBlank(),
     *     @Assert\Length(
     *       max=4000,
     *       maxMessage="Attribute placeholder is to long. It should have {{ limit }} character or less."
     *     )
     * })
     */
    public array $placeholder;

    /**
     * @var array
     *
     * @Assert\All({
     *     @Assert\NotBlank(),
     *     @Assert\Length(
     *       max=4000,
     *       maxMessage="Attribute hint is to long. It should have {{ limit }} character or less."
     *     )
     * })
     */
    public array $hint;

    /**
     * @var array
     */
    public array $groups;

    /**
     * @var mixed
     *
     * @Assert\Valid()
     */
    public $parameters;

    /**
     * @var ArrayCollection|AttributeOptionModel[]
     *
     * @Assert\Valid()
     *
     * @AppAssert\AttributeOptionDuplicates()
     *
     */
    public $options;

    /**
     */
    public function __construct()
    {
        $this->code = null;
        $this->type = null;
        $this->multilingual = false;
        $this->label = [];
        $this->placeholder = [];
        $this->hint = [];
        $this->groups = [];
        $this->parameters = null;
        $this->options = new ArrayCollection();
    }
}
