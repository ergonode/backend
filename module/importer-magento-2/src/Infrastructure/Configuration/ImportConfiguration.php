<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ImporterMagento2\Infrastructure\Configuration;

use Ergonode\ImporterMagento2\Infrastructure\Configuration\Column\ConfigurationColumnInterface;

/**
 */
class ImportConfiguration
{
    /**
     * @var ConfigurationColumnInterface[]
     */
    private array $columns;

    /**
     * @param array|ConfigurationColumnInterface ...$columns
     */
    public function __construct(ConfigurationColumnInterface ...$columns)
    {
        $this->columns = $columns;
    }

    /**
     * @param ConfigurationColumnInterface $column
     *
     * @return $this
     */
    public function add(ConfigurationColumnInterface $column): self
    {
        $this->columns[] = $column;

        return $this;
    }

    /**
     * @return ConfigurationColumnInterface[]
     */
    public function getColumn(): array
    {
        return $this->columns;
    }
}
