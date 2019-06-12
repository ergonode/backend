<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\CategoryTree\Domain\Event;

use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use Ergonode\Category\Domain\Entity\CategoryId;
use Ergonode\CategoryTree\Domain\Entity\CategoryTreeId;
use JMS\Serializer\Annotation as JMS;

/**
 */
class CategoryTreeCreatedEvent implements DomainEventInterface
{
    /**
     * @var CategoryTreeId
     *
     * @JMS\Type("Ergonode\CategoryTree\Domain\Entity\CategoryTreeId")
     */
    private $id;

    /**
     * @var string
     *
     * @JMS\Type("string")
     */
    private $name;

    /**
     * @var CategoryId|null
     *
     * @JMS\Type("Ergonode\Category\Domain\Entity\CategoryId")
     */
    private $categoryId;

    /**
     * @param CategoryTreeId  $id
     * @param string          $name
     * @param CategoryId|null $categoryId
     */
    public function __construct(CategoryTreeId $id, string $name, ?CategoryId $categoryId = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->categoryId = $categoryId;
    }

    /**
     * @return CategoryTreeId
     */
    public function getId(): CategoryTreeId
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
     * @return CategoryId|null
     */
    public function getCategoryId(): ?CategoryId
    {
        return $this->categoryId;
    }
}
