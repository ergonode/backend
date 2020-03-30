<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Segment\Infrastructure\Handler\Command;

use Ergonode\Condition\Domain\Service\ConditionCalculator;
use Ergonode\Product\Domain\Repository\ProductRepositoryInterface;
use Ergonode\Condition\Domain\Repository\ConditionSetRepositoryInterface;
use Ergonode\Segment\Domain\Command\CalculateProductInSegmentCommand;
use Webmozart\Assert\Assert;
use Ergonode\Segment\Domain\Repository\SegmentRepositoryInterface;
use Ergonode\Segment\Infrastructure\Service\SegmentProductService;

/**
 */
class CalculateProductInSegmentCommandHandler
{
    /**
     * @var ConditionCalculator
     */
    private ConditionCalculator $calculator;

    /**
     * @var ProductRepositoryInterface
     */
    private ProductRepositoryInterface $productRepository;

    /**
     * @var ConditionSetRepositoryInterface
     */
    private ConditionSetRepositoryInterface $conditionRepository;

    /**
     * @var SegmentRepositoryInterface
     */
    private SegmentRepositoryInterface $segmentRepository;

    /**
     * @var SegmentProductService
     */
    private SegmentProductService $service;

    /**
     * @param ConditionCalculator             $calculator
     * @param ProductRepositoryInterface      $productRepository
     * @param ConditionSetRepositoryInterface $conditionRepository
     * @param SegmentRepositoryInterface      $segmentRepository
     * @param SegmentProductService           $service
     */
    public function __construct(
        ConditionCalculator $calculator,
        ProductRepositoryInterface $productRepository,
        ConditionSetRepositoryInterface $conditionRepository,
        SegmentRepositoryInterface $segmentRepository,
        SegmentProductService $service
    ) {
        $this->calculator = $calculator;
        $this->productRepository = $productRepository;
        $this->conditionRepository = $conditionRepository;
        $this->segmentRepository = $segmentRepository;
        $this->service = $service;
    }

    /**
     * @param CalculateProductInSegmentCommand $command
     *
     * @throws \Exception
     */
    public function __invoke(CalculateProductInSegmentCommand $command): void
    {
        $productId = $command->getProductId();
        $segmentId = $command->getSegmentId();
        $product = $this->productRepository->load($productId);
        Assert::notNull($product);
        $segment = $this->segmentRepository->load($segmentId);
        Assert::notNull($segment);

        if($segment->hasConditionSet()) {
            $conditionSet = $this->conditionRepository->load($segment->getConditionSetId());

            Assert::notNull($conditionSet);

            $exists = $this->calculator->calculate($conditionSet, $product);

            if ($exists) {
                $this->service->mark($segmentId, $productId);
            } else {
                $this->service->unmark($segmentId, $productId);
            }
        }
    }
}
