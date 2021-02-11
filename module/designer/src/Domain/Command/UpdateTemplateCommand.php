<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Designer\Domain\Command;

use Doctrine\Common\Collections\ArrayCollection;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\SharedKernel\Domain\Aggregate\MultimediaId;
use Ergonode\SharedKernel\Domain\Aggregate\TemplateId;
use JMS\Serializer\Annotation as JMS;
use Webmozart\Assert\Assert;
use Ergonode\Designer\Domain\Entity\TemplateElementInterface;

class UpdateTemplateCommand implements TemplateCommandInterface
{
    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\TemplateId")
     */
    private TemplateId $id;

    /**
     * @JMS\Type("string")
     */
    private string $name;

    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\MultimediaId")
     */
    private ?MultimediaId $imageId;

    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\AttributeId")
     */
    private ?AttributeId $defaultLabel;

    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\AttributeId")
     */
    private ?AttributeId $defaultImage;

    /**
     * @var ArrayCollection|TemplateElementInterface[]
     *
     * @JMS\Type("ArrayCollection<Ergonode\Designer\Domain\Entity\TemplateElementInterface>")
     */
    private ArrayCollection $elements;

    public function __construct(
        TemplateId $id,
        string $name,
        ArrayCollection $elements,
        AttributeId $defaultLabel = null,
        AttributeId $defaultImage = null,
        ?MultimediaId $imageId = null
    ) {
        Assert::allIsInstanceOf(
            $elements,
            TemplateElementInterface::class,
            'Template elements should by %2$s class. Got: %s'
        );

        $this->id = $id;
        $this->name = $name;
        $this->elements = $elements;
        $this->defaultLabel = $defaultLabel;
        $this->defaultImage = $defaultImage;
        $this->imageId = $imageId;
    }

    public function getId(): TemplateId
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getImageId(): ?MultimediaId
    {
        return $this->imageId;
    }

    public function getDefaultLabel(): ?AttributeId
    {
        return $this->defaultLabel;
    }

    public function getDefaultImage(): ?AttributeId
    {
        return $this->defaultImage;
    }

    /**
     * @return ArrayCollection|TemplateElementInterface[]
     */
    public function getElements(): ArrayCollection
    {
        return $this->elements;
    }
}
