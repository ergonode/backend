<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Domain\Query;

use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\Core\Domain\ValueObject\Language;

interface OptionTranslationQueryInterface
{
    /**
     * @return array<string, string>
     */
    public function getLabels(AttributeId $attributeId, Language $language): array;
}
