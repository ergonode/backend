<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Completeness\Domain\Query;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use Ergonode\Completeness\Domain\ReadModel\CompletenessWidgetModel;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;

interface CompletenessQueryInterface
{
    public function hasCompleteness(ProductId $productId, Language $language): bool;

    /**
     * @return CompletenessWidgetModel[]
     */
    public function getCompletenessCount(Language $language): array;

    public function getAttributeLabel(AttributeId $attributeId, Language $language): string;
}
