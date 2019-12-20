<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Designer\Domain\Event;

use Ergonode\Core\Domain\Entity\AbstractId;
use Ergonode\Designer\Domain\Entity\TemplateGroupId;
use Ergonode\Designer\Domain\Entity\TemplateId;
use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use Ergonode\Multimedia\Domain\Entity\MultimediaId;
use JMS\Serializer\Annotation as JMS;

/**
 */
class TemplateCreatedEvent implements DomainEventInterface
{
    /**
     * @var TemplateId
     *
     * @JMS\Type("Ergonode\Designer\Domain\Entity\TemplateId")
     */
    private $id;

    /**
     * @var TemplateGroupId
     *
     * @JMS\Type("Ergonode\Designer\Domain\Entity\TemplateGroupId")
     */
    private $groupId;

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
     * @param TemplateId        $id
     * @param TemplateGroupId   $groupId
     * @param string            $name
     * @param MultimediaId|null $imageId
     */
    public function __construct(TemplateId $id, TemplateGroupId $groupId, string $name, ?MultimediaId $imageId = null)
    {
        $this->id = $id;
        $this->groupId = $groupId;
        $this->name = $name;
        $this->imageId = $imageId;
    }

    /**
     * @return TemplateId|AbstractId
     */
    public function getAggregateId(): AbstractId
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
     * @return null|MultimediaId
     */
    public function getImageId(): ?MultimediaId
    {
        return $this->imageId;
    }
}
