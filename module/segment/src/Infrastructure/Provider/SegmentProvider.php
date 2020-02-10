<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Segment\Infrastructure\Provider;

use Ergonode\Segment\Domain\Entity\Segment;
use Ergonode\SharedKernel\Domain\Aggregate\SegmentId;
use Ergonode\Segment\Domain\Repository\SegmentRepositoryInterface;
use Ergonode\Segment\Domain\ValueObject\SegmentCode;
use Ergonode\Segment\Infrastructure\Exception\SegmentGeneratorProviderException;

/**
 */
class SegmentProvider
{
    /**
     * @var SegmentRepositoryInterface
     */
    private SegmentRepositoryInterface $repository;

    /**
     * @var SegmentGeneratorProvider
     */
    private SegmentGeneratorProvider $provider;

    /**
     * @param SegmentRepositoryInterface $repository
     * @param SegmentGeneratorProvider   $provider
     */
    public function __construct(SegmentRepositoryInterface $repository, SegmentGeneratorProvider $provider)
    {
        $this->repository = $repository;
        $this->provider = $provider;
    }

    /**
     * @param SegmentCode $code
     *
     * @return Segment
     *
     * @throws SegmentGeneratorProviderException
     */
    public function provide(SegmentCode $code): Segment
    {
        $segmentId = SegmentId::fromCode($code->getValue());
        $segment = $this->repository->load($segmentId);
        if (null === $segment) {
            $generator = $this->provider->provide($code->getValue());
            $segment = $generator->generate($segmentId, $code->getValue());
            $this->repository->save($segment);
        }

        return $segment;
    }
}
