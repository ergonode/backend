<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Segment\Domain\Command;

use Ergonode\SharedKernel\Domain\Aggregate\ConditionSetId;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\SharedKernel\Domain\Aggregate\SegmentId;

class UpdateSegmentCommand implements SegmentCommandInterface
{
    private SegmentId $id;

    private TranslatableString $name;

    private TranslatableString $description;

    private ?ConditionSetId $conditionSetId;

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

    public function getId(): SegmentId
    {
        return $this->id;
    }

    public function getName(): TranslatableString
    {
        return $this->name;
    }

    public function getDescription(): TranslatableString
    {
        return $this->description;
    }

    public function getConditionSetId(): ?ConditionSetId
    {
        return $this->conditionSetId;
    }
}
