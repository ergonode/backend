<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Application\Installer;

use Ergonode\Product\Domain\Entity\Attribute\CreatedAtSystemAttribute;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Ergonode\Core\Application\Installer\InstallerInterface;
use Ergonode\Product\Domain\Entity\Attribute\CreatedBySystemAttribute;
use Ergonode\Product\Domain\Entity\Attribute\EditedAtSystemAttribute;
use Ergonode\Product\Domain\Entity\Attribute\EditedBySystemAttribute;

/**
 */
class AttributeInstaller implements InstallerInterface
{
    /**
     * @var AttributeRepositoryInterface
     */
    private AttributeRepositoryInterface $repository;

    /**
     * @param AttributeRepositoryInterface $repository
     */
    public function __construct(AttributeRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @throws \Exception
     */
    public function install(): void
    {
        $this->installCreateAtSystemAttribute();
        $this->installCreateBySystemAttribute();
        $this->installEditedAtSystemAttribute();
        $this->installEditedBySystemAttribute();
    }

    /**
     * @throws \Exception
     */
    public function installCreateAtSystemAttribute(): void
    {
        $attribute = new CreatedAtSystemAttribute(
            new TranslatableString(['en_GB' => 'Created at']),
            new TranslatableString(),
            new TranslatableString(),
        );

        $this->repository->save($attribute);
    }

    /**
     * @throws \Exception
     */
    public function installCreateBySystemAttribute(): void
    {
        $attribute = new CreatedBySystemAttribute(
            new TranslatableString(['en_GB' => 'Created by']),
            new TranslatableString(),
            new TranslatableString(),
        );

        $this->repository->save($attribute);
    }

    /**
     * @throws \Exception
     */
    public function installEditedAtSystemAttribute(): void
    {
        $attribute = new EditedAtSystemAttribute(
            new TranslatableString(['en_GB' => 'Edited at']),
            new TranslatableString(),
            new TranslatableString(),
        );

        $this->repository->save($attribute);
    }

    /**
     * @throws \Exception
     */
    public function installEditedBySystemAttribute(): void
    {
        $attribute = new EditedBySystemAttribute(
            new TranslatableString(['en_GB' => 'Edited by']),
            new TranslatableString(),
            new TranslatableString(),
        );

        $this->repository->save($attribute);
    }
}
