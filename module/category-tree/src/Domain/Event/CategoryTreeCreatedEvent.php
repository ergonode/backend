<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\CategoryTree\Domain\Event;

use Ergonode\CategoryTree\Domain\Entity\CategoryTreeId;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
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
    private $code;

    /**
     * @var TranslatableString
     *
     * @JMS\Type("Ergonode\Core\Domain\ValueObject\TranslatableString")
     */
    private $name;

    /**
     * @param CategoryTreeId     $id
     * @param string             $code
     * @param TranslatableString $name
     */
    public function __construct(CategoryTreeId $id, string $code, TranslatableString $name)
    {
        $this->id = $id;
        $this->code = $code;
        $this->name = $name;
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
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @return TranslatableString
     */
    public function getName(): TranslatableString
    {
        return $this->name;
    }
}
