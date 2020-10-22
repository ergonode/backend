<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Completeness\Domain\Query;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use Ergonode\Completeness\Domain\ReadModel\CompletenessReadModel;
use Ergonode\Completeness\Domain\ReadModel\CompletenessWidgetModel;

interface CompletenessQueryInterface
{
    public function getCompleteness(ProductId $productId, Language $language): CompletenessReadModel;

    /**
     * @return CompletenessWidgetModel[]
     */
    public function getCompletenessCount(Language $language): array;
}
