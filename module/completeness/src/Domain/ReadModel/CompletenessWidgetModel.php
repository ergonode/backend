<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Completeness\Domain\ReadModel;

/**
 *
 */
class CompletenessWidgetModel
{
    /**
     * @var string
     */
    private string $code;

    /**
     * @var string
     */
    private string $label;

    /**
     * @var int
     */
    private int $value;

    /**
     * @param string $code
     * @param string $label
     * @param int    $value
     */
    public function __construct(string $code, string $label, int $value)
    {
        $this->code = $code;
        $this->label = $label;
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * @return int
     */
    public function getValue(): int
    {
        return $this->value;
    }
}
