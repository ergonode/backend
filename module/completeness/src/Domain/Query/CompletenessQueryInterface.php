<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

namespace Ergonode\Completeness\Domain\Query;

use Ergonode\Completeness\Domain\ReadModel\CompletenessReadModel;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Editor\Domain\Entity\ProductDraftId;

/**
 */
interface CompletenessQueryInterface
{
    /**
     * @param ProductDraftId $draftId
     * @param Language       $language
     *
     * @return CompletenessReadModel
     */
    public function getCompleteness(ProductDraftId $draftId, Language $language): CompletenessReadModel;
}
