<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Designer\Application\Model\Form;

use Doctrine\Common\Collections\ArrayCollection;
use Ergonode\Attribute\Application\Validator\AttributeExists;
use Ergonode\Attribute\Application\Validator\AttributeTypeValid;
use Ergonode\Designer\Application\Model\Form\Type\TemplateElementTypeModel;
use Ergonode\Multimedia\Application\Validator\MultimediaExists;
use Symfony\Component\Validator\Constraints as Assert;

class TemplateFormModel
{
    /**
     * @Assert\NotBlank(message="Template name is required")
     * @Assert\Length(min="3", max="32")
     */
    public ?string $name;

    /**
     * @Assert\Uuid()
     *
     * @MultimediaExists()
     */
    public ?string $image;

    /**
     * @Assert\Uuid(strict=true)
     * @AttributeExists()

     * @AttributeTypeValid(type="TEXT")
     */
    public ?string $defaultLabel;

    /**
     * @Assert\Uuid(strict=true)
     * @AttributeExists()
     *
     * @AttributeTypeValid(type="IMAGE")
     */
    public ?string $defaultImage;

    /**
     * @var ArrayCollection|TemplateElementTypeModel[]
     *
     * @Assert\Valid()
     */
    public ArrayCollection $elements;

    public function __construct()
    {
        $this->name = null;
        $this->image = null;
        $this->defaultLabel = null;
        $this->defaultImage = null;
        $this->elements = new ArrayCollection();
    }
}
