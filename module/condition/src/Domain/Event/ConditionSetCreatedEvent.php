<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Condition\Domain\Event;

use Ergonode\Condition\Domain\Entity\ConditionSetId;
use Ergonode\Condition\Domain\ValueObject\ConditionSetCode;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\EventSourcing\Infrastructure\DomainEventInterface;
use JMS\Serializer\Annotation as JMS;

/**
 */
class ConditionSetCreatedEvent implements DomainEventInterface
{
    /**
     * @var ConditionSetId
     *
     * @JMS\Type("Ergonode\Condition\Domain\Entity\ConditionSetId")
     */
    private $id;

    /**
     * @var string
     *
     * @JMS\Type("Ergonode\Condition\Domain\ValueObject\ConditionSetCode")
     */
    private $code;

    /**
     * @var TranslatableString
     *
     * @JMS\Type("Ergonode\Core\Domain\ValueObject\TranslatableString")
     */
    private $name;

    /**
     * @var TranslatableString
     *
     * @JMS\Type("Ergonode\Core\Domain\ValueObject\TranslatableString")
     */
    private $description;

    /**
     * @param ConditionSetId     $id
     * @param ConditionSetCode   $code
     * @param TranslatableString $name
     * @param TranslatableString $description
     */
    public function __construct(
        ConditionSetId $id,
        ConditionSetCode $code,
        TranslatableString $name,
        TranslatableString $description
    ) {
        $this->id = $id;
        $this->code = $code;
        $this->name = $name;
        $this->description = $description;
    }

    /**
     * @return ConditionSetId
     */
    public function getId(): ConditionSetId
    {
        return $this->id;
    }

    /**
     * @return ConditionSetCode
     */
    public function getCode(): ConditionSetCode
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

    /**
     * @return TranslatableString
     */
    public function getDescription(): TranslatableString
    {
        return $this->description;
    }
}
