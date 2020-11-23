<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Infrastructure\Mapper;

use Ergonode\Category\Domain\Entity\AbstractCategory;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Exporter\Domain\Entity\Export;
use Ergonode\ExporterShopware6\Domain\Entity\Shopware6Channel;
use Ergonode\ExporterShopware6\Infrastructure\Model\Shopware6Category;
use Ergonode\SharedKernel\Domain\Aggregate\CategoryId;

interface Shopware6CategoryMapperInterface
{
    public function map(
        Shopware6Channel $channel,
        Export $export,
        Shopware6Category $shopware6Category,
        AbstractCategory $category,
        ?CategoryId $parentCategoryId = null,
        ?Language $language = null
    ): Shopware6Category;
}
