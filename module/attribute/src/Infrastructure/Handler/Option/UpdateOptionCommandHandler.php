<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Infrastructure\Handler\Option;

use Ergonode\Attribute\Domain\Command\Option\CreateOptionCommand;
use Ergonode\Attribute\Domain\Repository\OptionRepositoryInterface;
use Webmozart\Assert\Assert;
use Ergonode\Attribute\Domain\Command\Option\UpdateOptionCommand;

/**
 */
class UpdateOptionCommandHandler
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
     * @param UpdateOptionCommand $command
     *
     * @throws \Exception
     */
    public function __invoke(UpdateOptionCommand $command): void
    {
        $option = $this->repository->load($command->getId());
        Assert::notNull($option);
        $option->changeLabel($command->getLabel());
        $option->changeCode($command->getCode());

        $this->repository->save($option);
    }
}
