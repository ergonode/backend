<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Infrastructure\Query\Decorator;

use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use Ergonode\Product\Domain\Query\GetProductQueryInterface;
use Ergonode\Product\Domain\Repository\ProductRepositoryInterface;
use Webmozart\Assert\Assert;
use Ergonode\Workflow\Infrastructure\Query\ProductWorkflowQuery;

/**
 */
class WorkflowGetProductQueryDecorator implements GetProductQueryInterface
{
    /**
     * @var GetProductQueryInterface
     */
    private GetProductQueryInterface $query;

    /**
     * @var ProductRepositoryInterface
     */
    private ProductRepositoryInterface $productRepository;

    /**
     * @var ProductWorkflowQuery
     */
    private ProductWorkflowQuery $workflowQuery;

    /**
     * @param GetProductQueryInterface   $query
     * @param ProductRepositoryInterface $productRepository
     * @param ProductWorkflowQuery       $workflowQuery
     */
    public function __construct(
        GetProductQueryInterface $query,
        ProductRepositoryInterface $productRepository,
        ProductWorkflowQuery $workflowQuery
    ) {

        $this->query = $query;
        $this->productRepository = $productRepository;
        $this->workflowQuery = $workflowQuery;
    }

    /**
     * @param ProductId $productId
     * @param Language  $language
     *
     * @return array
     *
     * @throws \Exception
     */
    public function query(ProductId $productId, Language $language): array
    {
        $product = $this->productRepository->load($productId);
        Assert::notNull($product);

        $result = $this->query->query($productId, $language);

        return array_merge($result, $this->workflowQuery->getQuery($product, $language));
    }
}
