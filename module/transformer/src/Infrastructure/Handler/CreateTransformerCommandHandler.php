<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Transformer\Infrastructure\Handler;

use Ergonode\Transformer\Domain\Command\CreateTransformerCommand;
use Ergonode\Transformer\Domain\Entity\Transformer;
use Ergonode\Transformer\Domain\Repository\TransformerRepositoryInterface;

/**
 */
class CreateTransformerCommandHandler
{
    /**
     * @var TransformerRepositoryInterface
     */
    private $transformerRepository;

    /**
     * @param TransformerRepositoryInterface $transformerRepository
     */
    public function __construct(TransformerRepositoryInterface $transformerRepository)
    {
        $this->transformerRepository = $transformerRepository;
    }

    /**
     * @param CreateTransformerCommand $command
     */
    public function __invoke(CreateTransformerCommand $command)
    {
        $transformer = new Transformer($command->getId(), $command->getName(), $command->getKey());

        $this->transformerRepository->save($transformer);
    }
}
