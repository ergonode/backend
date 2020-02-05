<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Segment\Infrastructure\Handler\Command;

use Ergonode\Segment\Domain\Command\GenerateSegmentCommand;
use Ergonode\Segment\Domain\Repository\SegmentRepositoryInterface;
use Ergonode\Segment\Infrastructure\Provider\SegmentGeneratorProvider;
use \Ergonode\Segment\Infrastructure\Exception\SegmentGeneratorProviderException;

/**
 */
class GenerateSegmentCommandHandler
{
    /**
     * @var SegmentRepositoryInterface
     */
    private SegmentRepositoryInterface $repository;

    /**
     * @var SegmentGeneratorProvider
     */
    private SegmentGeneratorProvider $generator;

    /**
     * @param SegmentRepositoryInterface $repository
     * @param SegmentGeneratorProvider   $generator
     */
    public function __construct(SegmentRepositoryInterface $repository, SegmentGeneratorProvider $generator)
    {
        $this->repository = $repository;
        $this->generator = $generator;
    }

    /**
     * @param GenerateSegmentCommand $command
     *
     * @throws SegmentGeneratorProviderException
     */
    public function __invoke(GenerateSegmentCommand $command)
    {
        $generator = $this->generator->provide($command->getType());

        $segment = $generator->generate($command->getId(), $command->getCode());

        $this->repository->save($segment);
    }
}
