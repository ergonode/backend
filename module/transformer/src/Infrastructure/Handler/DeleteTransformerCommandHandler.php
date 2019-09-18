<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Transformer\Infrastructure\Handler;

use Ergonode\Transformer\Domain\Command\DeleteTransformerCommand;
use Ergonode\Transformer\Domain\Entity\Transformer;
use Ergonode\Transformer\Domain\Repository\TransformerRepositoryInterface;
use Webmozart\Assert\Assert;

/**
 */
class DeleteTransformerCommandHandler
{
    /**
     * @var TransformerRepositoryInterface
     */
    private $repository;

    /**
     * @param TransformerRepositoryInterface $repository
     */
    public function __construct(TransformerRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param DeleteTransformerCommand $command
     *
     * @throws \Exception
     */
    public function __invoke(DeleteTransformerCommand $command)
    {
        $transformer = $this->repository->load($command->getId());
        Assert::isInstanceOf($transformer, Transformer::class, sprintf('Can\'t find transformer with id "%s"', $command->getId()));

        $this->repository->delete($transformer);
    }
}
