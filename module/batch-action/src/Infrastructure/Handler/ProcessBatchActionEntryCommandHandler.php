<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\BatchAction\Infrastructure\Handler;

use Ergonode\BatchAction\Domain\Repository\BatchActionRepositoryInterface;
use Ergonode\Product\Domain\Repository\ProductRepositoryInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use Ergonode\Core\Infrastructure\Resolver\RelationshipsResolverInterface;
use Ergonode\BatchAction\Domain\Command\ProcessBatchActionEntryCommand;

class ProcessBatchActionEntryCommandHandler
{
    private BatchActionRepositoryInterface $batchActionRepository;

    private ProductRepositoryInterface $productRepository;

    private RelationshipsResolverInterface $relationshipsResolver;

    public function __construct(
        BatchActionRepositoryInterface $batchActionRepository,
        ProductRepositoryInterface $productRepository,
        RelationshipsResolverInterface $relationshipsResolver
    ) {
        $this->batchActionRepository = $batchActionRepository;
        $this->productRepository = $productRepository;
        $this->relationshipsResolver = $relationshipsResolver;
    }

    public function __invoke(ProcessBatchActionEntryCommand $command): void
    {
        $batchActionId = $command->getId();
        $resourceId = $command->getResourceId();
        $productId = new ProductId($resourceId->getValue());
        $product = $this->productRepository->load($productId);
        if ($product) {
            $relationships = $this->relationshipsResolver->resolve($product->getId());
            if (!$relationships->isEmpty()) {
                $message = '';
                $this->batchActionRepository->markEntryAsUnsuccess($batchActionId, $resourceId, $message);
            } else {
                $this->productRepository->delete($product);
                $this->batchActionRepository->markEntryAsSuccess($batchActionId, $resourceId);
            }
        }
    }
}
