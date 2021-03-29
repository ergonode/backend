<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Designer\Domain\Command;

use Doctrine\Common\Collections\ArrayCollection;
use Ergonode\Designer\Domain\Entity\TemplateElementInterface;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\SharedKernel\Domain\Aggregate\MultimediaId;
use Ergonode\SharedKernel\Domain\Aggregate\TemplateId;
use Webmozart\Assert\Assert;

class CreateTemplateCommand implements TemplateCommandInterface
{
    private TemplateId $templateId;

    private string $name;

    private ?MultimediaId $imageId;

    private ?AttributeId $defaultLabel;

    private ?AttributeId $defaultImage;

    /**
     * @var ArrayCollection|TemplateElementInterface[]
     */
    private ArrayCollection $elements;

    /**
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
            TemplateElementInterface::class,
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
     * @return ArrayCollection|TemplateElementInterface[]
     */
    public function getElements(): ArrayCollection
    {
        return $this->elements;
    }
}
