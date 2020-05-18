<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Completeness\Domain\ReadModel;

use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use JMS\Serializer\Annotation as JMS;

/**
 */
class CompletenessElementReadModel
{
    /**
     * @var AttributeId
     */
    private AttributeId $id;
    /**
     * @var string
     */
    private string $name;
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
     * @param string      $name
     * @param bool        $required
     * @param bool        $filled
     */
    public function __construct(AttributeId $id, string $name, bool $required, bool $filled)
    {
        $this->id = $id;
        $this->name = $name;
        $this->required = $required;
        $this->filled = $filled;
    }

    /**
     * @return AttributeId
     */
    public function getId(): AttributeId
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
