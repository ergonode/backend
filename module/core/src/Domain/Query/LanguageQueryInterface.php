<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Domain\Query;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\DataSetInterface;

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
     * @return array
     */
    public function getDictionaryActive(): array;

    /**
     * @return array
     */
    public function getLanguage(string $code): array;

    /**
     * @return array
     */
    public function getLanguageNodeInfo(Language $language): ?array;

    /**
     * @return Language[]
     */
    public function getInheritancePath(Language $language): array;

    public function getRootLanguage(): Language;

    public function getDataSet(): DataSetInterface;

    /**
     * @return array
     */
    public function autocomplete(
        string $search = null,
        int $limit = null,
        string $field = null,
        ?string $order = 'ASC'
    ): array;

    /**
     * @return array|null
     */
    public function getLanguageById(string $id): ?array;
}
