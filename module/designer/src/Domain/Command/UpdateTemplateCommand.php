<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Designer\Domain\Command;

use Doctrine\Common\Collections\ArrayCollection;
use Ergonode\Designer\Domain\Entity\TemplateElement;
use Ergonode\Designer\Domain\Entity\TemplateId;
use Ergonode\Multimedia\Domain\Entity\MultimediaId;
use JMS\Serializer\Annotation as JMS;

/**
 */
class UpdateTemplateCommand
{
    /**
     * @var TemplateId
     *
     * @JMS\Type("Ergonode\Designer\Domain\Entity\TemplateId")
     */
    private $id;

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
     * @var ArrayCollection
     *
     * @JMS\Type("ArrayCollection<string>")
     */
    private $sections;

    /**
     * @var ArrayCollection|TemplateElement[]
     *
     * @JMS\Type("ArrayCollection<Ergonode\Designer\Domain\Entity\TemplateElement>")
     */
    private $elements;

    /**
     * @param TemplateId        $id
     * @param string            $name
     * @param ArrayCollection   $elements
     * @param ArrayCollection   $sections
     * @param MultimediaId|null $imageId
     */
    public function __construct(TemplateId $id, string $name, ArrayCollection $elements, ArrayCollection $sections, ?MultimediaId $imageId = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->elements = $elements;
        $this->sections = $sections;
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
     * @return ArrayCollection
     */
    public function getSections(): ArrayCollection
    {
        return $this->sections;
    }

    /**
     * @return ArrayCollection|TemplateElement[]
     */
    public function getElements(): ArrayCollection
    {
        return $this->elements;
    }
}
