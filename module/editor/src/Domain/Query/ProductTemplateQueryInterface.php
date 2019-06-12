<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

namespace Ergonode\Editor\Domain\Query;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Editor\Domain\Entity\ProductDraftId;

/**
 */
interface ProductTemplateQueryInterface
{
    /**
     * @param ProductDraftId $productDraftId
     * @param Language       $language
     *
     * @return array
     */
    public function getTemplateView(ProductDraftId $productDraftId, Language $language): array;
}
