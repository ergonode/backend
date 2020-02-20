<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Core\Domain\Query;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\DataSetInterface;

/**
 */
interface LanguageQueryInterface
{
    /**
     * @return Language[]
     */
    public function getAll(): array;

    /**
     * @return Language[]
     */
    public function getActive(): array;

    /**
     * @return array
     */
    public function getDictionary(): array;

    /**
     * @param string $code
     *
     * @return array
     */
    public function getLanguage(string $code): array;

    /**
     * @return DataSetInterface
     */
    public function getDataSet(): DataSetInterface;

    /**
     * @param string|null $search
     * @param int|null    $limit
     * @param string|null $field
     * @param string|null $order
     *
     * @return array
     */
    public function autocomplete(
        string $search = null,
        int $limit = null,
        string $field = null,
        ?string $order = 'ASC'
    ): array;
}
