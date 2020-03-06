<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Domain\View;

use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\Attribute\Domain\ValueObject\AttributeCode;

/**
 */
class AttributeViewModel
{
    /**
     * @var AttributeId
     */
    private AttributeId $id;

    /**
     * @var AttributeCode
     */
    private AttributeCode $code;

    /**
     * @var string
     */
    private string $type;

    /**
     * @var array
     */
    private array $groups;

    /**
     * @param AttributeId   $id
     * @param AttributeCode $code
     * @param string        $type
     * @param array         $groups
     */
    public function __construct(AttributeId $id, AttributeCode $code, string $type, array $groups)
    {
        $this->id = $id;
        $this->code = $code;
        $this->type = $type;
        $this->groups = $groups;
    }

    /**
     * @return AttributeId
     */
    public function getId(): AttributeId
    {
        return $this->id;
    }

    /**
     * @return AttributeCode
     */
    public function getCode(): AttributeCode
    {
        return $this->code;
    }

    /**
     * @return array
     */
    public function getGroups(): array
    {
        return $this->groups;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }
}
