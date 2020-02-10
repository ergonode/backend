<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace Ergonode\Editor\Domain\Query;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\SharedKernel\Domain\Aggregate\ProductDraftId;
use Ergonode\Grid\DataSetInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;

/**
 */
interface DraftQueryInterface
{
    /**
     * @param ProductDraftId $templateId
     * @param Language       $language
     *
     * @return array
     */
    public function getDraftView(ProductDraftId $templateId, Language $language): array;

    /**
     * @param ProductId $productId
     *
     * @return null|ProductDraftId
     */
    public function getActualDraftId(ProductId $productId): ?ProductDraftId;

    /**
     * @return DataSetInterface
     */
    public function getDataSet(): DataSetInterface;
}
