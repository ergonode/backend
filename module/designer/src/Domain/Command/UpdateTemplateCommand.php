<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Designer\Domain\Command;

use Doctrine\Common\Collections\ArrayCollection;
use Ergonode\Designer\Domain\Entity\TemplateElement;
use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\SharedKernel\Domain\Aggregate\MultimediaId;
use Ergonode\SharedKernel\Domain\Aggregate\TemplateId;
use JMS\Serializer\Annotation as JMS;
use Webmozart\Assert\Assert;

/**
 */
class UpdateTemplateCommand implements DomainCommandInterface
{
    /**
     * @var TemplateId
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\TemplateId")
     */
    private TemplateId $id;

    /**
     * @var string
     *
     * @JMS\Type("string")
     */
    private string $name;

    /**
     * @var MultimediaId|null
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\MultimediaId")
     */
    private ?MultimediaId $imageId;

    /**
     * @var AttributeId|null
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\AttributeId")
     */
    private ?AttributeId $defaultLabel;

    /**
     * @var AttributeId|null
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\AttributeId")
     */
    private ?AttributeId $defaultImage;

    /**
     * @var ArrayCollection|TemplateElement[]
     *
     * @JMS\Type("ArrayCollection<Ergonode\Designer\Domain\Entity\TemplateElement>")
     */
    private ArrayCollection $elements;

    /**
     * @param TemplateId        $id
     * @param string            $name
     * @param ArrayCollection   $elements
     * @param AttributeId       $defaultLabel
     * @param AttributeId       $defaultImage
     * @param MultimediaId|null $imageId
     */
    public function __construct(
        TemplateId $id,
        string $name,
        ArrayCollection $elements,
        AttributeId $defaultLabel = null,
        AttributeId $defaultImage = null,
        ?MultimediaId $imageId = null
    ) {
        Assert::allIsInstanceOf($elements, TemplateElement::class, 'Template elements should by %2$s class. Got: %s');

        $this->id = $id;
        $this->name = $name;
        $this->elements = $elements;
        $this->defaultLabel = $defaultLabel;
        $this->defaultImage = $defaultImage;
        $this->imageId = $imageId;
    }

    /**
     * @return TemplateId
     */
    public function getId(): TemplateId
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return MultimediaId|null
     */
    public function getImageId(): ?MultimediaId
    {
        return $this->imageId;
    }

    /**
     * @return AttributeId|null
     */
    public function getDefaultLabel(): ?AttributeId
    {
        return $this->defaultLabel;
    }

    /**
     * @return AttributeId|null
     */
    public function getDefaultImage(): ?AttributeId
    {
        return $this->defaultImage;
    }

    /**
     * @return ArrayCollection|TemplateElement[]
     */
    public function getElements(): ArrayCollection
    {
        return $this->elements;
    }
}
