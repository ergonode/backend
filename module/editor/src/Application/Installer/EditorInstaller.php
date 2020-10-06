<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Editor\Application\Installer;

use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\Attribute\Domain\Repository\AttributeRepositoryInterface;
use Ergonode\Core\Application\Installer\InstallerInterface;
use Ergonode\Designer\Domain\Entity\Attribute\TemplateSystemAttribute;

/**
 */
class EditorInstaller implements InstallerInterface
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
        $this->installTemplateSystemAttribute();
    }

    /**
     * @throws \Exception
     */
    public function installTemplateSystemAttribute(): void
    {
        $attribute = new TemplateSystemAttribute(
            new TranslatableString(['en_GB' => 'Template']),
            new TranslatableString(),
            new TranslatableString(),
        );

        $this->repository->save($attribute);
    }
}
