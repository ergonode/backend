<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Designer\Application\Model\Form;

use Doctrine\Common\Collections\ArrayCollection;
use Ergonode\Designer\Application\Model\Form\Type\TemplateElementTypeModel;
use Symfony\Component\Validator\Constraints as Assert;

/**
 */
class TemplateFormModel
{
    /**
     * @var string
     *
     * @Assert\NotBlank(message="Template name is required")
     * @Assert\Length(min="3", max="32")
     */
    public $name;

    /**
     * @var string
     *
     * @Assert\Uuid()
     */
    public $image;

    /**
     * @var ArrayCollection|TemplateElementTypeModel[]
     *
     * @Assert\Valid()
     * @Assert\Collection()
     */
    public $elements;

    /**
     */
    public function __construct()
    {
        $this->elements = new ArrayCollection();
    }
}
