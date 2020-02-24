<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Designer\Domain\Event;

use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\SharedKernel\Domain\Aggregate\MultimediaId;
use Ergonode\SharedKernel\Domain\Aggregate\TemplateGroupId;
use Ergonode\SharedKernel\Domain\Aggregate\TemplateId;
use JMS\Serializer\Annotation as JMS;

/**
 */
class TemplateCreatedEvent implements DomainEventInterface
{
    /**
     * @var TemplateId
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\TemplateId")
     */
    private TemplateId $id;

    /**
     * @var TemplateGroupId
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\TemplateGroupId")
     */
    private TemplateGroupId $groupId;

    /**
     * @var string
     *
     * @JMS\Type("string")
     */
    private string $name;

    /**
     * @var AttributeId | null
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\AttributeId")
     */
    private ?AttributeId $defaultText;

    /**
     * @var AttributeId | null
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\AttributeId")
     */
    private ?AttributeId $defaultImage;

    /**
     * @var MultimediaId|null
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\MultimediaId")
     */
    private ?MultimediaId $imageId;

    /**
     * TemplateCreatedEvent constructor.
     *
     * @param TemplateId        $id
     * @param TemplateGroupId   $groupId
     * @param string            $name
     * @param AttributeId|null  $defaultText
     * @param AttributeId|null  $defaultImage
     * @param MultimediaId|null $imageId
     */
    public function __construct(
        TemplateId $id,
        TemplateGroupId $groupId,
        string $name,
        ?AttributeId $defaultText,
        ?AttributeId $defaultImage,
        ?MultimediaId $imageId
    ) {
        $this->id = $id;
        $this->groupId = $groupId;
        $this->name = $name;
        $this->defaultText = $defaultText;
        $this->defaultImage = $defaultImage;
        $this->imageId = $imageId;
    }


    /**
     * @return TemplateId
     */
    public function getAggregateId(): TemplateId
    {
        return $this->id;
    }

    /**
     * @return TemplateGroupId
     */
    public function getGroupId(): TemplateGroupId
    {
        return $this->groupId;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return null | AttributeId
     */
    public function getDefaultText(): ?AttributeId
    {
        return $this->defaultText;
    }

    /**
     * @return null | AttributeId
     */
    public function getDefaultImage(): ?AttributeId
    {
        return $this->defaultImage;
    }

    /**
     * @return null|MultimediaId
     */
    public function getImageId(): ?MultimediaId
    {
        return $this->imageId;
    }
}
