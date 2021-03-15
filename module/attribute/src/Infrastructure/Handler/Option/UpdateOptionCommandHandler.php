<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Infrastructure\Handler\Option;

use Ergonode\Attribute\Domain\Repository\OptionRepositoryInterface;
use Webmozart\Assert\Assert;
use Ergonode\Attribute\Domain\Command\Option\UpdateOptionCommand;

class UpdateOptionCommandHandler
{
    private OptionRepositoryInterface $repository;

    public function __construct(OptionRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
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
