<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Domain\Query;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\DataSetInterface;
use Ergonode\SharedKernel\Domain\Aggregate\LanguageId;

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

    public function getLanguageById(string $id): ?Language;

    /**
     * @param LanguageId[] $ids
     *
     * @return Language[]
     */
    public function getLanguagesByIds(array $ids): array;
}
