<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Infrastructure\Action;

use Ergonode\Category\Domain\Command\Tree\CreateTreeCommand;
use Ergonode\Category\Domain\Command\Tree\UpdateTreeCommand;
use Ergonode\Category\Domain\Query\TreeQueryInterface;
use Ergonode\Category\Domain\Repository\TreeRepositoryInterface;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\EventSourcing\Infrastructure\Bus\CommandBusInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ImportId;
use Ergonode\Transformer\Domain\Model\Record;
use Webmozart\Assert\Assert;

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
     * @var TreeQueryInterface
     */
    private TreeQueryInterface $query;

    /**
     * @var CommandBusInterface
     */
    private CommandBusInterface $commandBus;

    /**
     * @param TreeRepositoryInterface $repository
     * @param TreeQueryInterface      $query
     * @param CommandBusInterface     $commandBus
     */
    public function __construct(
        TreeRepositoryInterface $repository,
        TreeQueryInterface $query,
        CommandBusInterface $commandBus
    ) {
        $this->repository = $repository;
        $this->query = $query;
        $this->commandBus = $commandBus;
    }

    /**
     * @param ImportId $importId
     * @param Record   $record
     *
     * @throws \Exception
     */
    public function action(ImportId $importId, Record $record): void
    {
        /** @var string|null $code */
        $code = $record->has(self::CODE_FIELD) ? $record->get(self::CODE_FIELD) : null;

        Assert::notNull($code, 'Tree import required "code" field not exists');

        $tree = null;
        $treeId = $this->query->findTreeIdByCode($code);
        if ($treeId) {
            $tree = $this->repository->load($treeId);
        }

        $name = new TranslatableString();

        if (!$tree) {
            $command = new CreateTreeCommand($code, $name);
        } else {
            $command = new UpdateTreeCommand($treeId, $name);
        }

        $this->commandBus->dispatch($command, true);
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return self::TYPE;
    }
}
