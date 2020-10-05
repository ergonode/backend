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
     * @param GetProductQueryInterface   $query
     * @param ProductRepositoryInterface $productRepository
     */
    public function __construct(
        GetProductQueryInterface $query,
        ProductRepositoryInterface $productRepository
    ) {

        $this->query = $query;
        $this->productRepository = $productRepository;
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

        return $this->query->query($productId, $language);
    }
}
