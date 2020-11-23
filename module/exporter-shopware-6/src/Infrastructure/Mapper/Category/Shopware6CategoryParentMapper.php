<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Infrastructure\Mapper\Category;

use Ergonode\Category\Domain\Entity\AbstractCategory;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Exporter\Domain\Entity\Export;
use Ergonode\ExporterShopware6\Domain\Entity\Shopware6Channel;
use Ergonode\ExporterShopware6\Domain\Repository\Shopware6CategoryRepositoryInterface;
use Ergonode\ExporterShopware6\Infrastructure\Mapper\Shopware6CategoryMapperInterface;
use Ergonode\ExporterShopware6\Infrastructure\Model\Shopware6Category;
use Ergonode\SharedKernel\Domain\Aggregate\CategoryId;

class Shopware6CategoryParentMapper implements Shopware6CategoryMapperInterface
{
    private Shopware6CategoryRepositoryInterface $repository;

    public function __construct(Shopware6CategoryRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * {@inheritDoc}
     */
    public function map(
        Shopware6Channel $channel,
        Export $export,
        Shopware6Category $shopware6Category,
        AbstractCategory $category,
        ?CategoryId $parentCategoryId = null,
        ?Language $language = null
    ): Shopware6Category {
        if ($parentCategoryId) {
            $shopwareParentId = $this->repository->load($channel->getId(), $parentCategoryId);
            $shopware6Category->setParentId($shopwareParentId);
        }

        return $shopware6Category;
    }
}
