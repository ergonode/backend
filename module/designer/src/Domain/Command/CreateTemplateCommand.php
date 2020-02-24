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
class CreateTemplateCommand implements DomainCommandInterface
{
    /**
     * @var TemplateId
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\TemplateId")
     */
    private TemplateId $templateId;

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
    private ?AttributeId $defaultText;

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
     * CreateTemplateCommand constructor.
     *
     * @param string            $name
     * @param ArrayCollection   $elements
     * @param AttributeId|null  $defaultText
     * @param AttributeId|null  $defaultImage
     * @param MultimediaId|null $imageId
     *
     * @throws \Exception
     */
    public function __construct(
        string $name,
        ArrayCollection $elements,
        ?AttributeId $defaultText = null,
        ?AttributeId $defaultImage = null,
        ?MultimediaId $imageId = null
    ) {
        Assert::allIsInstanceOf(
            $elements->toArray(),
            TemplateElement::class,
            'Template elements should by %2$s class. Got: %s'
        );

        $this->templateId = TemplateId::generate();
        $this->name = $name;
        $this->defaultText = $defaultText;
        $this->defaultImage = $defaultImage;
        $this->elements = $elements;
        $this->imageId = $imageId;
    }

    /**
     * @return TemplateId
     */
    public function getId(): TemplateId
    {
        return $this->templateId;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return AttributeId|null
     */
    public function getDefaultText(): ?AttributeId
    {
        return $this->defaultText;
    }

    /**
     * @return AttributeId|null
     */
    public function getDefaultImage(): ?AttributeId
    {
        return $this->defaultImage;
    }

    /**
     * @return MultimediaId|null
     */
    public function getImageId(): ?MultimediaId
    {
        return $this->imageId;
    }

    /**
     * @return ArrayCollection|TemplateElement[]
     */
    public function getElements(): ArrayCollection
    {
        return $this->elements;
    }
}
