<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

namespace Ergonode\CategoryTree\Domain\Query;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Category\Domain\Entity\CategoryId;
use Ergonode\CategoryTree\Domain\Entity\CategoryTreeId;

/**
 */
interface TreeQueryInterface
{
    /**
     * @param CategoryTreeId  $id
     * @param Language        $language
     * @param CategoryId|null $nodeId
     *
     * @return array
     */
    public function getCategory(CategoryTreeId $id, Language $language, ?CategoryId $nodeId = null): array;
}
