<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\BatchAction\Infrastructure\Handler;

use Ergonode\BatchAction\Domain\Repository\BatchActionRepositoryInterface;
use Ergonode\BatchAction\Domain\Command\ProcessBatchActionResourceCommand;
use Ergonode\Product\Domain\Repository\ProductRepositoryInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use Ergonode\Core\Infrastructure\Resolver\RelationshipsResolverInterface;

class ProcessBatchActionResourceCommandHandler
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

    public function __invoke(ProcessBatchActionResourceCommand $command): void
    {
        $batchActionId = $command->getId();
        $resourceId = $command->getResourceId();
        $productId = new ProductId($resourceId->getValue());
        $product = $this->productRepository->load($productId);
        if ($product) {
            $relationships = $this->relationshipsResolver->resolve($product->getId());
            if (!$relationships->isEmpty()) {
                $message = '';
                $this->batchActionRepository->markResourceAsUnsuccess($batchActionId, $resourceId, $message);
            } else {
                $this->productRepository->delete($product);
                $this->batchActionRepository->markResourceAsSuccess($batchActionId, $resourceId);
            }
        }
    }
}
