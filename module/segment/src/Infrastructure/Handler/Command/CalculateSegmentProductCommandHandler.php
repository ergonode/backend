<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Segment\Infrastructure\Handler\Command;

use Ergonode\Condition\Domain\Service\ConditionCalculator;
use Ergonode\Product\Domain\Repository\ProductRepositoryInterface;
use Ergonode\Condition\Domain\Repository\ConditionSetRepositoryInterface;
use Ergonode\Segment\Application\Event\ProductExcludedFromSegmentEvent;
use Ergonode\Segment\Application\Event\ProductIncludedInSegmentEvent;
use Ergonode\SharedKernel\Domain\Bus\ApplicationEventBusInterface;
use Webmozart\Assert\Assert;
use Ergonode\Segment\Domain\Repository\SegmentRepositoryInterface;
use Ergonode\Segment\Infrastructure\Service\SegmentProductService;
use Ergonode\Segment\Domain\Command\CalculateSegmentProductCommand;

class CalculateSegmentProductCommandHandler
{
    private ConditionCalculator $calculator;

    private ProductRepositoryInterface $productRepository;

    private ConditionSetRepositoryInterface $conditionRepository;

    private SegmentRepositoryInterface $segmentRepository;

    private SegmentProductService $service;

    private ApplicationEventBusInterface $applicationEventBus;

    public function __construct(
        ConditionCalculator $calculator,
        ProductRepositoryInterface $productRepository,
        ConditionSetRepositoryInterface $conditionRepository,
        SegmentRepositoryInterface $segmentRepository,
        SegmentProductService $service,
        ApplicationEventBusInterface $applicationEventBus
    ) {
        $this->calculator = $calculator;
        $this->productRepository = $productRepository;
        $this->conditionRepository = $conditionRepository;
        $this->segmentRepository = $segmentRepository;
        $this->service = $service;
        $this->applicationEventBus = $applicationEventBus;
    }

    /**
     * @throws \Exception
     */
    public function __invoke(CalculateSegmentProductCommand $command): void
    {
        $productId = $command->getProductId();
        $segmentId = $command->getSegmentId();
        $product = $this->productRepository->load($productId);
        Assert::notNull($product);
        $segment = $this->segmentRepository->load($segmentId);
        Assert::notNull($segment);

        if ($segment->hasConditionSet()) {
            $conditionSet = $this->conditionRepository->load($segment->getConditionSetId());

            Assert::notNull($conditionSet);

            $exists = $this->calculator->calculate($conditionSet, $product);
            $wasAvailable = $this->service->wasAvailable($segmentId, $productId);

            if ($exists) {
                $this->service->mark($segmentId, $productId);
                if (!$wasAvailable) {
                    $this->applicationEventBus->dispatch(new ProductIncludedInSegmentEvent($productId, $segmentId));
                }
            } else {
                $this->service->unmark($segmentId, $productId);
                if ($wasAvailable) {
                    $this->applicationEventBus->dispatch(new ProductExcludedFromSegmentEvent($productId, $segmentId));
                }
            }
        }
    }
}
