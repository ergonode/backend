<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Segment\Infrastructure\Handler\Command;

use Ergonode\Segment\Domain\Command\CalculateSegmentProductCommand;
use Ergonode\Condition\Domain\Service\ConditionCalculator;
use Ergonode\Product\Domain\Repository\ProductRepositoryInterface;
use Ergonode\Condition\Domain\Repository\ConditionSetRepositoryInterface;
use Webmozart\Assert\Assert;
use Ergonode\Segment\Domain\Query\SegmentQueryInterface;
use Ergonode\Segment\Domain\Repository\SegmentRepositoryInterface;
use Ergonode\Segment\Domain\Entity\SegmentId;
use Ergonode\Segment\Infrastructure\Service\SegmentProductService;

/**
 */
class CalculateSegmentProductCommandHandler
{
    /**
     * @var SegmentQueryInterface
     */
    private SegmentQueryInterface $query;

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
     * @param SegmentQueryInterface           $query
     * @param ConditionCalculator             $calculator
     * @param ProductRepositoryInterface      $productRepository
     * @param ConditionSetRepositoryInterface $conditionRepository
     * @param SegmentRepositoryInterface      $segmentRepository
     * @param SegmentProductService           $service
     */
    public function __construct(
        SegmentQueryInterface $query,
        ConditionCalculator $calculator,
        ProductRepositoryInterface $productRepository,
        ConditionSetRepositoryInterface $conditionRepository,
        SegmentRepositoryInterface $segmentRepository,
        SegmentProductService $service
    ) {
        $this->query = $query;
        $this->calculator = $calculator;
        $this->productRepository = $productRepository;
        $this->conditionRepository = $conditionRepository;
        $this->segmentRepository = $segmentRepository;
        $this->service = $service;
    }

    /**
     * @param CalculateSegmentProductCommand $command
     *
     * @throws \Exception
     */
    public function __invoke(CalculateSegmentProductCommand $command): void
    {
        $segmentIds = $this->query->getAllSegmentIds();
        if (!empty($segmentIds)) {
            $productId = $command->getProductId();
            $product = $this->productRepository->load($productId);

            foreach ($segmentIds as $segmentId) {
                $segmentId = new SegmentId($segmentId);
                $segment = $this->segmentRepository->load($segmentId);
                Assert::notNull($segment);
                $conditionSet = $this->conditionRepository->load($segment->getConditionSetId());
                Assert::notNull($conditionSet);

                if($product) {
                    $exists = $this->calculator->calculate($conditionSet, $product);

                    if ($exists) {
                        if (!$this->service->exists($segmentId, $productId)) {
                            $this->service->add($segmentId, $productId);
                        }
                    } else {
                        if ($this->service->exists($segmentId, $productId)) {
                            $this->service->remove($segmentId, $productId);
                        }
                    }
                } else {
                    if ($this->service->exists($segmentId, $productId)) {
                        $this->service->remove($segmentId, $productId);
                    }
                }
            }
        }
    }
}
