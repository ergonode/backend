<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Infrastructure\Handler\Option;

use Ergonode\Attribute\Domain\Command\Option\CreateOptionCommand;
use Ergonode\Attribute\Domain\Entity\Option\SimpleOption;
use Ergonode\Attribute\Domain\Repository\OptionRepositoryInterface;

class CreateOptionCommandHandler
{
    private OptionRepositoryInterface $repository;

    public function __construct(OptionRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @throws \Exception
     */
    public function __invoke(CreateOptionCommand $command): void
    {
        $option = new SimpleOption(
            $command->getId(),
            $command->getAttributeId(),
            $command->getCode(),
            $command->getLabel()
        );

        $this->repository->save($option);
    }
}
