<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Domain\Query;

use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Attribute\Domain\ValueObject\OptionKey;
use Ergonode\SharedKernel\Domain\AggregateId;
use Ergonode\Grid\DataSetInterface;

interface OptionQueryInterface
{
    /**
     * @return array
     */
    public function getList(AttributeId $attributeId, Language $language): array;

    /**
     * @return array
     */
    public function getAll(?AttributeId $attributeId = null): array;

    /**
     * @return array
     */
    public function getOptions(AttributeId $attributeId): array;

    public function getDataSet(AttributeId $attributeId, Language $language): DataSetInterface;

    public function findIdByAttributeIdAndCode(AttributeId $id, OptionKey $code): ?AggregateId;

    public function findKey(AggregateId $id): ?OptionKey;
}
