<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Ergonode\Editor\Domain\Query;

use Ergonode\SharedKernel\Domain\Aggregate\ProductDraftId;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;

/**
 */
interface DraftQueryInterface
{
    /**
     * @param ProductDraftId $templateId
     *
     * @return array
     */
    public function getDraftView(ProductDraftId $templateId): array;

    /**
     * @param ProductId $productId
     *
     * @return null|ProductDraftId
     */
    public function getActualDraftId(ProductId $productId): ?ProductDraftId;
}
