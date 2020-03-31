<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Infrastructure\Handler\Option;

use Ergonode\Attribute\Domain\Repository\OptionRepositoryInterface;
use Ergonode\Attribute\Domain\Command\Option\DeleteOptionCommand;
use Webmozart\Assert\Assert;
use Ergonode\Attribute\Domain\Entity\AbstractOption;

/**
 */
class DeleteOptionCommandHandler
{
    /**
     * @var OptionRepositoryInterface
     */
    private OptionRepositoryInterface $repository;

    /**
     * @param OptionRepositoryInterface $repository
     */
    public function __construct(OptionRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param DeleteOptionCommand $command
     *
     * @throws \Exception
     */
    public function __invoke(DeleteOptionCommand $command): void
    {
        $option = $this->repository->load($command->getId());
        Assert::isInstanceOf(
            $option,
            AbstractOption::class,
            sprintf('Option with ID "%s" not found', $command->getId())
        );
        $this->repository->delete($option);
    }
}
