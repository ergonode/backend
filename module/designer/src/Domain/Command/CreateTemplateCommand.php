<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Designer\Domain\Command;

use Doctrine\Common\Collections\ArrayCollection;
use Ergonode\Designer\Domain\Entity\TemplateElement;
use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\SharedKernel\Domain\Aggregate\MultimediaId;
use Ergonode\SharedKernel\Domain\Aggregate\TemplateId;
use JMS\Serializer\Annotation as JMS;
use Webmozart\Assert\Assert;

class CreateTemplateCommand implements DomainCommandInterface
{
    /**
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\TemplateId")
     */
    private TemplateId $templateId;

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
     * @var ArrayCollection|TemplateElement[]
     *
     * @JMS\Type("ArrayCollection<Ergonode\Designer\Domain\Entity\TemplateElement>")
     */
    private ArrayCollection $elements;

    /**
     * CreateTemplateCommand constructor.
     *
     *
     * @throws \Exception
     */
    public function __construct(
        string $name,
        ArrayCollection $elements,
        ?AttributeId $defaultLabel = null,
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
        $this->defaultLabel = $defaultLabel;
        $this->defaultImage = $defaultImage;
        $this->elements = $elements;
        $this->imageId = $imageId;
    }

    public function getId(): TemplateId
    {
        return $this->templateId;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDefaultLabel(): ?AttributeId
    {
        return $this->defaultLabel;
    }

    public function getDefaultImage(): ?AttributeId
    {
        return $this->defaultImage;
    }

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
