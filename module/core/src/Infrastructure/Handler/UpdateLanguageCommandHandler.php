<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Core\Infrastructure\Handler;

use Ergonode\Core\Domain\Command\UpdateLanguageCommand;
use Ergonode\Core\Domain\Repository\LanguageRepositoryInterface;

/**
 */
class UpdateLanguageCommandHandler
{
    /**
     * @var LanguageRepositoryInterface;
     */
    private $repository;

    /**
     * @param LanguageRepositoryInterface $repository
     */
    public function __construct(LanguageRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param UpdateLanguageCommand $command
     */
    public function __invoke(UpdateLanguageCommand $command)
    {
        $this->repository->save($command->getCode(), $command->isActive());
    }
}
