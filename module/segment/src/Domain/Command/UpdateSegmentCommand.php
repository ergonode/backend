<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Segment\Domain\Command;

use Ergonode\SharedKernel\Domain\Aggregate\ConditionSetId;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\EventSourcing\Infrastructure\DomainCommandInterface;
use Ergonode\SharedKernel\Domain\Aggregate\SegmentId;
use JMS\Serializer\Annotation as JMS;

/**
 */
class UpdateSegmentCommand implements DomainCommandInterface
{
    /**
     * @var SegmentId
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\SegmentId")
     */
    private SegmentId $id;

    /**
     * @var TranslatableString
     *
     * @JMS\Type("Ergonode\Core\Domain\ValueObject\TranslatableString")
     */
    private TranslatableString $name;

    /**
     * @var TranslatableString
     *
     * @JMS\Type("Ergonode\Core\Domain\ValueObject\TranslatableString")
     */
    private TranslatableString $description;

    /**
     * @var ConditionSetId|null
     *
     * @JMS\Type("Ergonode\SharedKernel\Domain\Aggregate\ConditionSetId")
     */
    private ?ConditionSetId $conditionSetId;

    /**
     * @param SegmentId           $id
     * @param TranslatableString  $name
     * @param TranslatableString  $description
     * @param ConditionSetId|null $conditionSetId
     */
    public function __construct(
        SegmentId $id,
        TranslatableString $name,
        TranslatableString $description,
        ?ConditionSetId $conditionSetId = null
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->conditionSetId = $conditionSetId;
    }

    /**
     * @return SegmentId
     */
    public function getId(): SegmentId
    {
        return $this->id;
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

    /**
     * @return ConditionSetId|null
     */
    public function getConditionSetId(): ?ConditionSetId
    {
        return $this->conditionSetId;
    }
}
