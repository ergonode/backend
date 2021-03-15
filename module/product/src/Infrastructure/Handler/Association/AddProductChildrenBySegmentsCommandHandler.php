<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Infrastructure\Handler\Association;

use Ergonode\Product\Domain\Repository\ProductRepositoryInterface;
use Webmozart\Assert\Assert;
use Ergonode\Product\Domain\Entity\AbstractAssociatedProduct;
use Ergonode\Product\Domain\Command\Relations\AddProductChildrenBySegmentsCommand;
use Ergonode\Segment\Domain\Query\SegmentProductsQueryInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use Ergonode\Product\Domain\Entity\SimpleProduct;

class AddProductChildrenBySegmentsCommandHandler
{
    private ProductRepositoryInterface $repository;

    private SegmentProductsQueryInterface $query;

    public function __construct(
        ProductRepositoryInterface $repository,
        SegmentProductsQueryInterface $query
    ) {
        $this->repository = $repository;
        $this->query = $query;
    }

    /**
     * @throws \Exception
     */
    public function __invoke(AddProductChildrenBySegmentsCommand $command): void
    {
        /** @var AbstractAssociatedProduct $product */
        $product = $this->repository->load($command->getId());

        Assert::isInstanceOf(
            $product,
            AbstractAssociatedProduct::class,
            sprintf('Can\'t find associated product with id "%s"', $command->getId())
        );

        foreach ($command->getSegments() as $segmentId) {
            foreach ($this->query->getProducts($segmentId) as $productId) {
                $child = $this->repository->load(new ProductId($productId));
                if ($child instanceof SimpleProduct) {
                    $product->addChild($child);
                }
            }
        }

        $this->repository->save($product);
    }
}
