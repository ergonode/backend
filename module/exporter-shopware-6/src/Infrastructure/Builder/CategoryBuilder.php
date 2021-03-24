<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Infrastructure\Builder;

use Ergonode\Category\Domain\Entity\AbstractCategory;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Channel\Domain\Entity\Export;
use Ergonode\ExporterShopware6\Domain\Entity\Shopware6Channel;
use Ergonode\ExporterShopware6\Infrastructure\Mapper\CategoryMapperInterface;
use Ergonode\ExporterShopware6\Infrastructure\Model\Shopware6Category;
use Ergonode\SharedKernel\Domain\Aggregate\CategoryId;

class CategoryBuilder
{
    /**
     * @var CategoryMapperInterface[]
     */
    private array $collection;

    public function __construct(CategoryMapperInterface ...$collection)
    {
        $this->collection = $collection;
    }

    public function build(
        Shopware6Channel $channel,
        Export $export,
        Shopware6Category $shopware6Category,
        AbstractCategory $category,
        ?CategoryId $parentCategoryId = null,
        ?Language $language = null
    ): Shopware6Category {
        foreach ($this->collection as $mapper) {
            $shopware6Category = $mapper->map(
                $channel,
                $export,
                $shopware6Category,
                $category,
                $parentCategoryId,
                $language
            );
        }

        return $shopware6Category;
    }
}
