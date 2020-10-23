<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Transformer\Infrastructure\Handler;

use Ergonode\Transformer\Domain\Command\CreateTransformerCommand;
use Ergonode\Transformer\Domain\Factory\TransformerFactory;
use Ergonode\Transformer\Domain\Repository\TransformerRepositoryInterface;

class CreateTransformerCommandHandler
{
    private TransformerRepositoryInterface $repository;

    private TransformerFactory $factory;

    public function __construct(TransformerRepositoryInterface $repository, TransformerFactory $factory)
    {
        $this->repository = $repository;
        $this->factory = $factory;
    }

    /**
     * @throws \Exception
     */
    public function __invoke(CreateTransformerCommand $command)
    {
        $transformer = $this->factory->create($command->getId(), $command->getName(), $command->getKey());

        $this->repository->save($transformer);
    }
}
