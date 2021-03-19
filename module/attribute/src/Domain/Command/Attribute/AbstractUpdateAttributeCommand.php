<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Domain\Command\Attribute;

use Ergonode\Attribute\Domain\Command\AttributeCommandInterface;
use Ergonode\Attribute\Domain\ValueObject\AttributeScope;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeGroupId;
use Webmozart\Assert\Assert;

abstract class AbstractUpdateAttributeCommand implements AttributeCommandInterface
{
    private AttributeId $id;

    private AttributeScope $scope;

    /**
     * @var AttributeGroupId[]
     */
    private array $groups;

    private TranslatableString $label;

    private TranslatableString $hint;

    private TranslatableString $placeholder;

    /**
     * @param AttributeGroupId[] $groups
     */
    public function __construct(
        AttributeId $id,
        TranslatableString $label,
        TranslatableString $hint,
        TranslatableString $placeholder,
        AttributeScope $scope,
        array $groups = []
    ) {
        Assert::allIsInstanceOf($groups, AttributeGroupId::class);

        $this->id = $id;
        $this->scope = $scope;
        $this->label = $label;
        $this->hint = $hint;
        $this->placeholder = $placeholder;
        $this->groups = $groups;
    }

    public function getId(): AttributeId
    {
        return $this->id;
    }

    /**
     * @return AttributeGroupId[]
     */
    public function getGroups(): array
    {
        return $this->groups;
    }

    public function hasGroup(AttributeGroupId $id): bool
    {
        foreach ($this->groups as $group) {
            if ($group->isEqual($id)) {
                return true;
            }
        }

        return false;
    }

    public function getLabel(): TranslatableString
    {
        return $this->label;
    }

    public function getHint(): TranslatableString
    {
        return $this->hint;
    }

    public function getPlaceholder(): TranslatableString
    {
        return $this->placeholder;
    }

    public function getScope(): AttributeScope
    {
        return $this->scope;
    }
}
