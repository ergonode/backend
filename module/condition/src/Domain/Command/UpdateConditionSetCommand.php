<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Condition\Domain\Command;

use Ergonode\Condition\Domain\Condition\ConditionInterface;
use Ergonode\Condition\Domain\Entity\ConditionSetId;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use JMS\Serializer\Annotation as JMS;
use Webmozart\Assert\Assert;

/**
 */
class UpdateConditionSetCommand
{
    /**
     * @var ConditionSetId
     *
     * @JMS\Type("Ergonode\Condition\Domain\Entity\ConditionSetId")
     */
    private $id;

    /**
     * @var TranslatableString|null
     *
     * @JMS\Type("Ergonode\Core\Domain\ValueObject\TranslatableString")
     */
    private $name;

    /**
     * @var TranslatableString|null
     *
     * @JMS\Type("Ergonode\Core\Domain\ValueObject\TranslatableString")
     */
    private $description;

    /**
     * @var ConditionInterface[]
     *
     * @JMS\Type("array<Ergonode\Condition\Domain\Condition\ConditionInterface>")
     */
    private $conditions;

    /**
     * @param ConditionSetId          $id
     * @param array                   $conditions
     * @param TranslatableString|null $name
     * @param TranslatableString|null $description
     */
    public function __construct(
        ConditionSetId $id,
        array $conditions,
        ?TranslatableString $name = null,
        ?TranslatableString $description = null
    ) {
        Assert::allIsInstanceOf($conditions, ConditionInterface::class);

        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->conditions = $conditions;
    }

    /**
     * @return ConditionSetId
     */
    public function getId(): ConditionSetId
    {
        return $this->id;
    }

    /**
     * @return bool
     */
    public function hasName(): bool
    {
        return $this->name instanceof TranslatableString;
    }

    /**
     * @return TranslatableString|null
     */
    public function getName(): ?TranslatableString
    {
        return $this->name;
    }

    /**
     * @return bool
     */
    public function hasDescription(): bool
    {
        return $this->description instanceof TranslatableString;
    }

    /**
     * @return TranslatableString|null
     */
    public function getDescription(): ?TranslatableString
    {
        return $this->description;
    }

    /**
     * @return ConditionInterface[]
     */
    public function getConditions(): array
    {
        return $this->conditions;
    }
}
