<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterFile\Infrastructure\DataStructure;

/**
 */
class LanguageData
{
    /**
     * @var string[]
     */
    private array $values = [];

    /**
     * @param string      $key
     * @param string|null $data
     */
    public function set(string $key, ?string $data = null): void
    {
        $this->values[$key] = $data;
    }

    /**
     * @return LanguageData[]
     */
    public function getValues(): array
    {
        return $this->values;
    }
}
