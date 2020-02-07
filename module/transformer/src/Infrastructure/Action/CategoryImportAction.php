<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Transformer\Infrastructure\Action;

use Ergonode\Category\Domain\Command\CreateCategoryCommand;
use Ergonode\Category\Domain\Command\UpdateCategoryCommand;
use Ergonode\Category\Domain\Entity\CategoryId;
use Ergonode\Category\Domain\Repository\CategoryRepositoryInterface;
use Ergonode\Category\Domain\ValueObject\CategoryCode;
use Ergonode\EventSourcing\Infrastructure\Bus\CommandBusInterface;
use Ergonode\Transformer\Domain\Model\Record;
use Webmozart\Assert\Assert;

/**
 */
class CategoryImportAction implements ImportActionInterface
{
    public const TYPE = 'CATEGORY';

    public const CODE_FIELD = 'code';
    public const NAME_FIELD = 'name';

    /**
     * @var CategoryRepositoryInterface
     */
    private CategoryRepositoryInterface $categoryRepository;

    /**
     * @var CommandBusInterface
     */
    private CommandBusInterface $commandBus;

    /**
     * @param CategoryRepositoryInterface $categoryRepository
     * @param CommandBusInterface         $commandBus
     */
    public function __construct(CategoryRepositoryInterface $categoryRepository, CommandBusInterface $commandBus)
    {
        $this->categoryRepository = $categoryRepository;
        $this->commandBus = $commandBus;
    }

    /**
     * @param Record $record
     *
     * @throws \Exception
     */
    public function action(Record $record): void
    {
        $code = $record->get(self::CODE_FIELD) ? new CategoryCode($record->get(self::CODE_FIELD)->getValue()) : null;
        $name = $record->get(self::NAME_FIELD) ? $record->get(self::NAME_FIELD)->getValue() : null;
        Assert::notNull($code, 'Category import required "code" field not exists');
        Assert::notNull($name, 'Category import required "name" field not exists');

        $categoryId = CategoryId::fromCode($code);
        $category = $this->categoryRepository->load($categoryId);

        if(!$category) {
            $command = new CreateCategoryCommand($code, $name);
        } else {
            $command = new UpdateCategoryCommand($categoryId, $name);
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
