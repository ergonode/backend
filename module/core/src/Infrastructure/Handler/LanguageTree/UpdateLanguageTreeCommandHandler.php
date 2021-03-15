<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Infrastructure\Handler\LanguageTree;

use Ergonode\Core\Domain\Command\LanguageTree\UpdateLanguageTreeCommand;
use Ergonode\Core\Domain\Repository\LanguageTreeRepositoryInterface;
use Webmozart\Assert\Assert;

class UpdateLanguageTreeCommandHandler
{
    private LanguageTreeRepositoryInterface $repository;

    public function __construct(LanguageTreeRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(UpdateLanguageTreeCommand $command): void
    {
        $tree = $this->repository->load();
        Assert::notNull($tree);

        $tree->updateLanguages($command->getLanguages());
        $this->repository->save($tree);
    }
}
