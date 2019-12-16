<?php

/**
 * Copyright © Ergonaut Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Channel\Infrastructure\Service;

use Ergonode\Channel\Domain\Entity\Channel;
use Ergonode\Condition\Domain\Repository\ConditionSetRepositoryInterface;
use Ergonode\Condition\Domain\Service\ConditionCalculator;
use Ergonode\Product\Domain\Entity\AbstractProduct;
use Ergonode\Segment\Domain\Repository\SegmentRepositoryInterface;
use Webmozart\Assert\Assert;

/**
 */
class ChannelValidationService
{
    /**
     * @var SegmentRepositoryInterface
     */
    private $segmentRepository;

    /**
     * @var ConditionSetRepositoryInterface
     */
    private $conditionSetRepository;

    /**
     * @var ConditionCalculator
     */
    private $service;

    /**
     * @param AbstractProduct $product
     * @param Channel         $channel
     *
     * @return bool
     */
    public function isValid(AbstractProduct $product, Channel $channel): bool
    {
        $segment = $this->segmentRepository->load($channel->getSegmentId());
        Assert::notNull($segment, sprintf('Segment %s not exists', $channel->getSegmentId()->getValue()));
        $conditionSet = $this->conditionSetRepository->load($segment->getConditionSetId());
        Assert::notNull($conditionSet, sprintf('ConditionSet %s not exists', $segment->getConditionSetId()->getValue()));

        return $this->service->calculate($conditionSet, $product);
    }
}
