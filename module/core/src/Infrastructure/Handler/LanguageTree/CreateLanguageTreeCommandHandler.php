<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Infrastructure\Handler\LanguageTree;

use Ergonode\Core\Domain\Command\LanguageTree\CreateLanguageTreeCommand;
use Ergonode\Core\Domain\Entity\LanguageTree;
use Ergonode\Core\Domain\Repository\LanguageTreeRepositoryInterface;
use Ergonode\Core\Domain\ValueObject\LanguageNode;

class CreateLanguageTreeCommandHandler
{
    private LanguageTreeRepositoryInterface $repository;

    public function __construct(LanguageTreeRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(CreateLanguageTreeCommand $command): void
    {
        $root = new LanguageNode($command->getRootLanguage());

        $tree = new LanguageTree($root);

        $this->repository->save($tree);
    }
}
