<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Designer\Domain\Event;

use Ergonode\SharedKernel\Domain\Aggregate\TemplateGroupId;
use Ergonode\SharedKernel\Domain\Aggregate\TemplateId;
use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use Ergonode\SharedKernel\Domain\Aggregate\MultimediaId;
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
    private $id;

    /**
     * @var TemplateGroupId
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\TemplateGroupId")
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
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\MultimediaId")
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
     * @return null|MultimediaId
     */
    public function getImageId(): ?MultimediaId
    {
        return $this->imageId;
    }
}
