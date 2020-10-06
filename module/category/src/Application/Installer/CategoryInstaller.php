<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Category\Application\Installer;

use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Ergonode\Core\Application\Installer\InstallerInterface;
use Ergonode\Category\Domain\Entity\Attribute\CategorySystemAttribute;
use Ergonode\EventSourcing\Infrastructure\Bus\CommandBusInterface;
use Ergonode\Category\Domain\Command\Tree\CreateTreeCommand;

/**
 */
class CategoryInstaller implements InstallerInterface
{
    /**
     * @var AttributeRepositoryInterface
     */
    private AttributeRepositoryInterface $repository;

    /**
     * @var CommandBusInterface
     */
    private CommandBusInterface $commandBus;

    /**
     * @param AttributeRepositoryInterface $repository
     * @param CommandBusInterface          $commandBus
     */
    public function __construct(AttributeRepositoryInterface $repository, CommandBusInterface $commandBus)
    {
        $this->repository = $repository;
        $this->commandBus = $commandBus;
    }

    /**
     * @throws \Exception
     */
    public function install(): void
    {
        $this->installCategoryTree();
        $this->installCategorySystemAttribute();
    }

    /**
     * @throws \Exception
     */
    private function installCategoryTree(): void
    {
        $command = new CreateTreeCommand(
            'default',
            new TranslatableString(['en_GB' => 'Default']),
        );

        $this->commandBus->dispatch($command);
    }


    /**
     * @throws \Exception
     */
    private function installCategorySystemAttribute(): void
    {
        $attribute = new CategorySystemAttribute(
            new TranslatableString(['en_GB' => 'Categories']),
            new TranslatableString(),
            new TranslatableString(),
        );

        $this->repository->save($attribute);
    }
}
