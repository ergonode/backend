<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Transformer\Infrastructure\Handler;

use Ergonode\Transformer\Domain\Command\GenerateTransformerCommand;
use Ergonode\Transformer\Domain\Repository\TransformerRepositoryInterface;
use Ergonode\Transformer\Infrastructure\Generator\TransformerGenerator;

/**
 */
class GenerateTransformerCommandHandler
{
    /**
     * @var TransformerRepositoryInterface
     */
    private $transformerRepository;

    /**
     * @var TransformerGenerator
     */
    private $generator;

    /**
     * @param TransformerRepositoryInterface $transformerRepository
     * @param TransformerGenerator           $generator
     */
    public function __construct(TransformerRepositoryInterface $transformerRepository, TransformerGenerator $generator)
    {
        $this->transformerRepository = $transformerRepository;
        $this->generator = $generator;
    }

    /**
     * @param GenerateTransformerCommand $command
     *
     * @throws \Exception
     */
    public function __invoke(GenerateTransformerCommand $command)
    {
        $transformer = $this->generator->generate($command->getName(), $command->getType());

        $this->transformerRepository->save($transformer);
    }
}
