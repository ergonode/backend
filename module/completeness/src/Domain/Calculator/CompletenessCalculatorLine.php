<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Completeness\Domain\Calculator;

use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;

class CompletenessCalculatorLine
{
    /**
     * @var AttributeId
     */
    private AttributeId $id;

    /**
     * @var bool
     *
     * @JMS\Exclude()
     */
    private bool $required;

    /**
     * @var bool
     *
     * @JMS\Exclude()
     */
    private bool $filled;

    /**
     * @param AttributeId $id
     * @param bool        $required
     * @param bool        $filled
     */
    public function __construct(AttributeId $id, bool $required, bool $filled)
    {
        $this->id = $id;
        $this->required = $required;
        $this->filled = $filled;
    }

    /**
     * @return AttributeId
     */
    public function getAttributeId(): AttributeId
    {
        return $this->id;
    }

    /**
     * @return bool
     */
    public function isRequired(): bool
    {
        return $this->required;
    }

    /**
     * @return bool
     */
    public function isFilled(): bool
    {
        return $this->filled;
    }
}
