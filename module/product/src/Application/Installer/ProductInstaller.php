<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Product\Application\Installer;

use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Ergonode\Core\Application\Installer\InstallerInterface;
use Ergonode\EventSourcing\Infrastructure\Bus\CommandBusInterface;
use Ergonode\Product\Domain\Entity\Attribute\ProductTypeSystemAttribute;
use Ergonode\Designer\Domain\Entity\Attribute\DefaultLabelSystemAttribute;
use Ergonode\Designer\Domain\Entity\Attribute\DefaultImageSystemAttribute;

/**
 */
class ProductInstaller implements InstallerInterface
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
        $this->installProductTypeSystemAttribute();
    }

    /**
     * @throws \Exception
     */
    private function installProductTypeSystemAttribute(): void
    {
        $attribute = new ProductTypeSystemAttribute(
            new TranslatableString(['en_GB' => 'Product type']),
            new TranslatableString(),
            new TranslatableString(),
        );

        $this->repository->save($attribute);
    }

    /**
     * @throws \Exception
     */
    private function installDefaultImageSystemAttribute(): void
    {
        $attribute = new DefaultImageSystemAttribute(
            new TranslatableString(['en_GB' => 'Default image']),
            new TranslatableString(),
            new TranslatableString(),
        );

        $this->repository->save($attribute);
    }

    /**
     * @throws \Exception
     */
    private function installDefaultLabelSystemAttribute(): void
    {
        $attribute = new DefaultLabelSystemAttribute(
            new TranslatableString(['en_GB' => 'Default label']),
            new TranslatableString(),
            new TranslatableString(),
        );

        $this->repository->save($attribute);
    }
}
