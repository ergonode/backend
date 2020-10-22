<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Core\Infrastructure\Handler\LanguageTree;

use Ergonode\Core\Domain\Command\LanguageTree\CreateLanguageTreeCommand;
use Ergonode\Core\Domain\Entity\LanguageTree;
use Ergonode\Core\Domain\Repository\LanguageTreeRepositoryInterface;
use Ergonode\Core\Domain\ValueObject\LanguageNode;

class CreateLanguageTreeCommandHandler
{
    /**
     * @var LanguageTreeRepositoryInterface
     */
    private LanguageTreeRepositoryInterface $repository;

    /**
     * @param LanguageTreeRepositoryInterface $repository
     */
    public function __construct(LanguageTreeRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param CreateLanguageTreeCommand $command
     */
    public function __invoke(CreateLanguageTreeCommand $command): void
    {
        $root = new LanguageNode($command->getRootLanguage());

        $tree = new LanguageTree($root);

        $this->repository->save($tree);
    }
}
