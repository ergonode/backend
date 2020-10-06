<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ProductCollection\Application\Installer;

use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\Core\Application\Installer\InstallerInterface;
use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Ergonode\EventSourcing\Infrastructure\Bus\CommandBusInterface;
use Ergonode\ProductCollection\Domain\Command\CreateProductCollectionTypeCommand;
use Ergonode\ProductCollection\Domain\ValueObject\ProductCollectionTypeCode;
use Ergonode\ProductCollection\Domain\Entity\Attribute\ProductCollectionSystemAttribute;

/**
 */
class ProductCollectionInstaller implements InstallerInterface
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
        $command =  new CreateProductCollectionTypeCommand(
            new ProductCollectionTypeCode('cross-sell'),
            new TranslatableString(['en_GB' => 'Cross sell'])
        );
        $this->commandBus->dispatch($command);

        $command =  new CreateProductCollectionTypeCommand(
            new ProductCollectionTypeCode('up-sell'),
            new TranslatableString(['en_GB' => 'Up sell'])
        );
        $this->commandBus->dispatch($command);

        $this->installCreateAtSystemProductCollection();
    }

    /**
     * @throws \Exception
     */
    public function installCreateAtSystemProductCollection(): void
    {
        $attribute = new ProductCollectionSystemAttribute(
            new TranslatableString(['en_GB' => 'Product collections']),
            new TranslatableString(),
            new TranslatableString(),
        );

        $this->repository->save($attribute);
    }
}
