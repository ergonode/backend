<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Designer\Application\Model\Form;

use Doctrine\Common\Collections\ArrayCollection;
use Ergonode\Attribute\Infrastructure\Validator\AttributeExists;
use Ergonode\Attribute\Infrastructure\Validator\AttributeTypeValid;
use Ergonode\Designer\Application\Model\Form\Type\TemplateElementTypeModel;
use Ergonode\Multimedia\Application\Validator\Constraint\MultimediaExists;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Symfony\Component\Validator\Constraints as Assert;

/**
 */
class TemplateFormModel
{
    /**
     * @var string | null
     *
     * @Assert\NotBlank(message="Template name is required")
     * @Assert\Length(min="3", max="32")
     */
    public ?string $name;

    /**
     * @var string | null
     *
     * @Assert\Uuid()
     *
     * @MultimediaExists()
     */
    public ?string $image;

    /**
     * @var AttributeId | null
     *
     * @AttributeExists()
     *
     * @AttributeTypeValid(type="TEXT")
     *
     */
    public ?AttributeId $defaultLabel;

    /**
     * @var AttributeId | null
     *
     * @AttributeExists()
     *
     * @AttributeTypeValid(type="IMAGE")
     */
    public ?AttributeId $defaultImage;

    /**
     * @var ArrayCollection|TemplateElementTypeModel[]
     *
     * @Assert\Valid()
     * @Assert\Collection()
     */
    public ArrayCollection $elements;

    /**
     */
    public function __construct()
    {
        $this->name = null;
        $this->image = null;
        $this->defaultLabel = null;
        $this->defaultImage = null;
        $this->elements = new ArrayCollection();
    }
}
