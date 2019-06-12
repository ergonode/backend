<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Designer\Domain\Command;

use Doctrine\Common\Collections\ArrayCollection;
use Ergonode\Designer\Domain\Entity\AbstractTemplateElement;
use Ergonode\Designer\Domain\Entity\TemplateId;
use Ergonode\Multimedia\Domain\Entity\MultimediaId;
use JMS\Serializer\Annotation as JMS;
use Webmozart\Assert\Assert;

/**
 */
class CreateTemplateCommand
{
    /**
     * @var TemplateId
     *
     * @JMS\Type("Ergonode\Designer\Domain\Entity\TemplateId")
     */
    private $templateId;

    /**
     * @var string
     *
     * @JMS\Type("string")
     */
    private $name;

    /**
     * @var MultimediaId|null
     *
     * @JMS\Type("Ergonode\Multimedia\Domain\Entity\MultimediaId")
     */
    private $imageId;

    /**
     * @var ArrayCollection|string;
     *
     * @JMS\Type("ArrayCollection<string>")
     */
    private $sections;

    /**
     * @var ArrayCollection|AbstractTemplateElement[]
     *
     * @JMS\Type("ArrayCollection<Ergonode\Designer\Domain\Entity\AbstractTemplateElement>")
     */
    private $elements;

    /**
     * @param string            $name
     * @param ArrayCollection   $elements
     * @param ArrayCollection   $sections
     * @param MultimediaId|null $imageId
     *
     * @throws \Exception
     */
    public function __construct(string $name, ArrayCollection $elements, ArrayCollection $sections, ?MultimediaId $imageId = null)
    {
        Assert::allIsInstanceOf($elements->toArray(), AbstractTemplateElement::class, 'Template elements should by %2$s class. Got: %s');

        $this->templateId = TemplateId::generate();
        $this->name = $name;
        $this->sections = $sections;
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
     * @return MultimediaId|null
     */
    public function getImageId(): ?MultimediaId
    {
        return $this->imageId;
    }

    /**
     * @return ArrayCollection|string
     */
    public function getSections()
    {
        return $this->sections;
    }

    /**
     * @return ArrayCollection|AbstractTemplateElement[]
     */
    public function getElements(): ArrayCollection
    {
        return $this->elements;
    }
}
