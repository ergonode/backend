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
     * @param ProductDraftId $draftId
     *
     * @return array
     */
    public function getDraftView(ProductDraftId $draftId): array;

    /**
     * @param ProductId $productId
     *
     * @return null|ProductDraftId
     */
    public function getActualDraftId(ProductId $productId): ?ProductDraftId;

    /**
     * @param AttributeId $attributeId
     *
     * @return ProductDraftId[]
     */
    public function getNotAppliedWithAttribute(AttributeId $attributeId): array;

    /**
     * @param ProductDraftId $id
     *
     * @return ProductId
     */
    public function getProductId(ProductDraftId $id): ProductId;
}
