<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Infrastructure\Action;

use Ergonode\Category\Domain\Command\CreateCategoryCommand;
use Ergonode\Category\Domain\Command\UpdateCategoryCommand;
use Ergonode\Category\Domain\ValueObject\CategoryCode;
use Ergonode\EventSourcing\Infrastructure\Bus\CommandBusInterface;
use Ergonode\Transformer\Domain\Model\Record;
use Webmozart\Assert\Assert;
use Ergonode\SharedKernel\Domain\Aggregate\ImportId;
use Ergonode\Category\Domain\Query\CategoryQueryInterface;
use Ergonode\SharedKernel\Domain\Aggregate\CategoryId;

/**
 */
class CategoryImportAction implements ImportActionInterface
{
    public const TYPE = 'CATEGORY';

    public const CODE_FIELD = 'code';
    public const NAME_FIELD = 'name';

    /**
     * @var CategoryQueryInterface
     */
    private CategoryQueryInterface $query;

    /**
     * @var CommandBusInterface
     */
    private CommandBusInterface $commandBus;

    /**
     * @param CategoryQueryInterface $query
     * @param CommandBusInterface    $commandBus
     */
    public function __construct(
        CategoryQueryInterface $query,
        CommandBusInterface $commandBus
    ) {
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
        $code = $record->has(self::CODE_FIELD) ? new CategoryCode($record->get(self::CODE_FIELD)) : null;
        $name = $record->hasTranslation(self::NAME_FIELD) ? $record->getTranslation(self::NAME_FIELD) : null;
        Assert::notNull($code, 'Category import required "code" field not exists');
        Assert::notNull($name, 'Category import required "name" field not exists');

        $categoryId = $this->query->findIdByCode($code);

        if (!$categoryId) {
            $command = new CreateCategoryCommand(CategoryId::generate(), $code, $name);
        } else {
            $command = new UpdateCategoryCommand($categoryId, $name);
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
