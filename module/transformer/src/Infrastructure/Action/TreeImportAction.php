<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Transformer\Infrastructure\Action;

use Ergonode\EventSourcing\Infrastructure\Bus\CommandBusInterface;
use Ergonode\SharedKernel\Domain\Aggregate\CategoryTreeId;
use Ergonode\Transformer\Domain\Model\Record;
use Webmozart\Assert\Assert;
use Ergonode\CategoryTree\Domain\Command\CreateTreeCommand;
use Ergonode\CategoryTree\Domain\Repository\TreeRepositoryInterface;
use Ergonode\CategoryTree\Domain\Command\UpdateTreeCommand;
use Ergonode\Core\Domain\ValueObject\TranslatableString;

/**
 */
class TreeImportAction implements ImportActionInterface
{
    public const TYPE = 'TREE';

    public const CODE_FIELD = 'code';

    /**
     * @var TreeRepositoryInterface
     */
    private TreeRepositoryInterface $repository;

    /**
     * @var CommandBusInterface
     */
    private CommandBusInterface $commandBus;

    /**
     * @param TreeRepositoryInterface $repository
     * @param CommandBusInterface     $commandBus
     */
    public function __construct(TreeRepositoryInterface $repository, CommandBusInterface $commandBus)
    {
        $this->repository = $repository;
        $this->commandBus = $commandBus;
    }

    /**
     * @param Record $record
     *
     * @throws \Exception
     */
    public function action(Record $record): void
    {
        /** @var string|null $code */
        $code = $record->get(self::CODE_FIELD) ? $record->get(self::CODE_FIELD)->getValue() : null;

        Assert::notNull($code, 'Tree import required "code" field not exists');

        $treeId = CategoryTreeId::fromKey($code);
        $tree = $this->repository->load($treeId);

        $name = new TranslatableString();

        if (!$tree) {
            $command = new CreateTreeCommand($code, $name);
        } else {
            $command = new UpdateTreeCommand($treeId, $name);
        }

        $this->commandBus->dispatch($command);
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return self::TYPE;
    }
}
