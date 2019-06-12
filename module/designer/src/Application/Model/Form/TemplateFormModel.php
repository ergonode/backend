<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Designer\Application\Model\Form;

use Doctrine\Common\Collections\ArrayCollection;
use Ergonode\Designer\Application\Model\Form\Type\TemplateElementTypeModel;
use Ergonode\Designer\Application\Model\Form\Type\TemplateSectionTypeModel;
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
     * @var ArrayCollection|TemplateSectionTypeModel[]
     *
     * @Assert\Valid()
     * @Assert\Collection()
     */
    public $sections;

    /**
     */
    public function __construct()
    {
        $this->elements = new ArrayCollection();
        $this->sections = new ArrayCollection();
    }
}
