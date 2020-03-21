<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Ergonode\Attribute\Domain\Query;

use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Attribute\Domain\ValueObject\OptionKey;
use Ergonode\SharedKernel\Domain\AggregateId;

/**
 */
interface OptionQueryInterface
{
    /**
     * @param AttributeId $attributeId
     * @param Language    $language
     *
     * @return array
     */
    public function getList(AttributeId $attributeId, Language $language): array;

    /**
     * @param AttributeId $id
     * @param OptionKey   $code
     *
     * @return AggregateId|null
     */
    public function findIdByAttributeIdAndCode(AttributeId $id, OptionKey $code): ?AggregateId;
}
