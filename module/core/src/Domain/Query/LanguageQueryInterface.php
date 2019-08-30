<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
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
    public function getActiveLanguagesCodes(): array;

    /**
     * @param string $code
     *
     * @return array
     */
    public function getLanguage(string $code): array;

    /**
     * @param array $codes
     *
     * @return array
     */
    public function getLanguages(array $codes): array;

    /**
     * @return DataSetInterface
     */
    public function getDataSet(): DataSetInterface;
}
