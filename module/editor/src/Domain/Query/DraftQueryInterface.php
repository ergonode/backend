<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Ergonode\Editor\Domain\Query;

use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\SharedKernel\Domain\Aggregate\ProductDraftId;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;

interface DraftQueryInterface
{
    /**
     * @return array
     */
    public function getDraftView(ProductDraftId $draftId): array;

    public function getActualDraftId(ProductId $productId): ?ProductDraftId;

    /**
     * @return ProductDraftId[]
     */
    public function getNotAppliedWithAttribute(AttributeId $attributeId): array;

    public function getProductId(ProductDraftId $id): ProductId;
}
