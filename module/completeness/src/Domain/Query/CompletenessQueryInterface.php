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
    /**
     * @param ProductId $productId
     * @param Language  $language
     *
     * @return CompletenessReadModel
     */
    public function getCompleteness(ProductId $productId, Language $language): CompletenessReadModel;

    /**
     * @param Language $language
     *
     * @return CompletenessWidgetModel[]
     */
    public function getCompletenessCount(Language $language): array;
}
