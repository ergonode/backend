<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Reader\Infrastructure\Handler;

use Ergonode\Reader\Domain\Command\DeleteReaderCommand;
use Ergonode\Reader\Domain\Entity\Reader;
use Ergonode\Reader\Domain\Repository\ReaderRepositoryInterface;
use Webmozart\Assert\Assert;

/**
 */
class DeleteReaderCommandHandler
{
    /**
     * @var ReaderRepositoryInterface
     */
    private $repository;

    /**
     * @param ReaderRepositoryInterface $repository
     */
    public function __construct(ReaderRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param DeleteReaderCommand $command
     *
     * @throws \Exception
     */
    public function __invoke(DeleteReaderCommand $command)
    {
        $role = $this->repository->load($command->getId());
        Assert::isInstanceOf($role, Reader::class, sprintf('Can\'t find reader with id "%s"', $command->getId()));

        $this->repository->delete($role);
    }
}
