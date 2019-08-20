<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Core\Domain\Query;

use Ergonode\Grid\DataSetInterface;

/**
 */
interface LanguageQueryInterface
{
    /**
     * @return array
     */
    public function getLanguagesCodes(): array;

    /**
     * @return array
     */
    public function getSystemLanguagesCodes(): array;

    /**
     * @param string $id
     *
     * @return array
     */
    public function getLanguage(string $id): array;

    /**
     * @return DataSetInterface
     */
    public function getDataSet(): DataSetInterface;
}
