<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Application\Installer;

use Ergonode\Core\Application\Installer\InstallerInterface;
use Ergonode\EventSourcing\Infrastructure\Bus\CommandBusInterface;
use Ergonode\Account\Domain\Command\Role\CreateRoleCommand;
use Ergonode\Account\Domain\ValueObject\Privilege;
use Ergonode\SharedKernel\Domain\Aggregate\RoleId;

/**
 */
class AccountInstaller implements InstallerInterface
{
    /**
     * @var CommandBusInterface
     */
    private CommandBusInterface $commandBus;

    /**
     * @param CommandBusInterface $commandBus
     */
    public function __construct(CommandBusInterface $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    /**
     * @throws \Exception
     */
    public function install(): void
    {
        $this->installSuperAdminRole();
        $this->installAdminRole();
        $this->installDataInputerRole();
        $this->installCategoryManagerRole();
    }

    /**
     * @throws \Exception
     */
    private function installSuperAdminRole(): void
    {
        $command = new CreateRoleCommand(
            'Superadmin',
            '',
            [
                new Privilege('USER_ROLE_CREATE'),
                new Privilege('USER_ROLE_READ'),
                new Privilege('USER_ROLE_UPDATE'),
                new Privilege('USER_ROLE_DELETE'),
                new Privilege('USER_CREATE'),
                new Privilege('USER_READ'),
                new Privilege('USER_UPDATE'),
                new Privilege('USER_DELETE'),
                new Privilege('SETTINGS_CREATE'),
                new Privilege('SETTINGS_UPDATE'),
                new Privilege('SETTINGS_READ'),
                new Privilege('SETTINGS_DELETE'),
            ],
            true
        );

        $this->commandBus->dispatch($command);
    }

    /**
     * @return RoleId
     *
     * @throws \Exception
     */
    private function installAdminRole(): RoleId
    {
        $command = new CreateRoleCommand(
            'Admin',
            'Manages Ergonode system, manages access to all permissions for other users.',
            [
                new Privilege('READER_CREATE'),
                new Privilege('READER_READ'),
                new Privilege('READER_UPDATE'),
                new Privilege('READER_DELETE'),
                new Privilege('IMPORT_CREATE'),
                new Privilege('IMPORT_READ'),
                new Privilege('IMPORT_UPDATE'),
                new Privilege('IMPORT_DELETE'),
                new Privilege('CATEGORY_TREE_CREATE'),
                new Privilege('CATEGORY_TREE_READ'),
                new Privilege('CATEGORY_TREE_UPDATE'),
                new Privilege('CATEGORY_TREE_DELETE'),
                new Privilege('CATEGORY_CREATE'),
                new Privilege('CATEGORY_READ'),
                new Privilege('CATEGORY_UPDATE'),
                new Privilege('CATEGORY_DELETE'),
                new Privilege('PRODUCT_CREATE'),
                new Privilege('PRODUCT_READ'),
                new Privilege('PRODUCT_UPDATE'),
                new Privilege('PRODUCT_DELETE'),
                new Privilege('ATTRIBUTE_CREATE'),
                new Privilege('ATTRIBUTE_READ'),
                new Privilege('ATTRIBUTE_UPDATE'),
                new Privilege('ATTRIBUTE_DELETE'),
                new Privilege('ATTRIBUTE_GROUP_CREATE'),
                new Privilege('ATTRIBUTE_GROUP_READ'),
                new Privilege('ATTRIBUTE_GROUP_UPDATE'),
                new Privilege('ATTRIBUTE_GROUP_DELETE'),
                new Privilege('TEMPLATE_DESIGNER_CREATE'),
                new Privilege('TEMPLATE_DESIGNER_READ'),
                new Privilege('TEMPLATE_DESIGNER_UPDATE'),
                new Privilege('TEMPLATE_DESIGNER_DELETE'),
                new Privilege('MULTIMEDIA_CREATE'),
                new Privilege('MULTIMEDIA_READ'),
                new Privilege('MULTIMEDIA_UPDATE'),
                new Privilege('MULTIMEDIA_DELETE'),
                new Privilege('USER_ROLE_CREATE'),
                new Privilege('USER_ROLE_READ'),
                new Privilege('USER_ROLE_UPDATE'),
                new Privilege('USER_ROLE_DELETE'),
                new Privilege('USER_CREATE'),
                new Privilege('USER_READ'),
                new Privilege('USER_UPDATE'),
                new Privilege('USER_DELETE'),
                new Privilege('WORKFLOW_CREATE'),
                new Privilege('WORKFLOW_READ'),
                new Privilege('WORKFLOW_UPDATE'),
                new Privilege('WORKFLOW_DELETE'),
                new Privilege('SEGMENT_CREATE'),
                new Privilege('SEGMENT_READ'),
                new Privilege('SEGMENT_UPDATE'),
                new Privilege('SEGMENT_DELETE'),
                new Privilege('CHANNEL_CREATE'),
                new Privilege('CHANNEL_READ'),
                new Privilege('CHANNEL_UPDATE'),
                new Privilege('CHANNEL_DELETE'),
                new Privilege('PRODUCT_COLLECTION_CREATE'),
                new Privilege('PRODUCT_COLLECTION_UPDATE'),
                new Privilege('PRODUCT_COLLECTION_READ'),
                new Privilege('PRODUCT_COLLECTION_DELETE'),
                new Privilege('SETTINGS_CREATE'),
                new Privilege('SETTINGS_UPDATE'),
                new Privilege('SETTINGS_READ'),
                new Privilege('SETTINGS_DELETE'),
            ]
        );

        $this->commandBus->dispatch($command);

        return $command->getId();
    }

    /**
     * @throws \Exception
     */
    private function installDataInputerRole(): void
    {
        $command = new CreateRoleCommand(
            'Data inputer',
            'Enriches product information: manages attributes and updates products data. Has access '.
            'to product categories and updates them. Is responsible for data enrichment.',
            [
                new Privilege('IMPORT_CREATE'),
                new Privilege('IMPORT_READ'),
                new Privilege('IMPORT_UPDATE'),
                new Privilege('IMPORT_DELETE'),
                new Privilege('CATEGORY_TREE_READ'),
                new Privilege('CATEGORY_TREE_UPDATE'),
                new Privilege('CATEGORY_CREATE'),
                new Privilege('CATEGORY_READ'),
                new Privilege('CATEGORY_UPDATE'),
                new Privilege('PRODUCT_CREATE'),
                new Privilege('PRODUCT_READ'),
                new Privilege('PRODUCT_UPDATE'),
                new Privilege('ATTRIBUTE_CREATE'),
                new Privilege('ATTRIBUTE_READ'),
                new Privilege('ATTRIBUTE_UPDATE'),
                new Privilege('ATTRIBUTE_DELETE'),
                new Privilege('ATTRIBUTE_GROUP_CREATE'),
                new Privilege('ATTRIBUTE_GROUP_READ'),
                new Privilege('ATTRIBUTE_GROUP_UPDATE'),
                new Privilege('ATTRIBUTE_GROUP_DELETE'),
                new Privilege('TEMPLATE_DESIGNER_CREATE'),
                new Privilege('TEMPLATE_DESIGNER_READ'),
                new Privilege('TEMPLATE_DESIGNER_UPDATE'),
                new Privilege('MULTIMEDIA_CREATE'),
                new Privilege('MULTIMEDIA_READ'),
                new Privilege('MULTIMEDIA_UPDATE'),
                new Privilege('PRODUCT_COLLECTION_CREATE'),
                new Privilege('PRODUCT_COLLECTION_UPDATE'),
                new Privilege('PRODUCT_COLLECTION_READ'),
                new Privilege('PRODUCT_COLLECTION_DELETE'),
            ]
        );
        $this->commandBus->dispatch($command);
    }

    /**
     * @throws \Exception
     */
    private function installCategoryManagerRole(): void
    {
        $command = new CreateRoleCommand(
            'Category manager',
            'Assures product data are correct and ready to publish. Manages product categories, '.
            'verifies products statuses. Is responsible for product data completeness.',
            [
                new Privilege('IMPORT_CREATE'),
                new Privilege('IMPORT_READ'),
                new Privilege('IMPORT_UPDATE'),
                new Privilege('IMPORT_DELETE'),
                new Privilege('CATEGORY_TREE_CREATE'),
                new Privilege('CATEGORY_TREE_READ'),
                new Privilege('CATEGORY_TREE_UPDATE'),
                new Privilege('CATEGORY_TREE_DELETE'),
                new Privilege('CATEGORY_CREATE'),
                new Privilege('CATEGORY_READ'),
                new Privilege('CATEGORY_UPDATE'),
                new Privilege('CATEGORY_DELETE'),
                new Privilege('PRODUCT_CREATE'),
                new Privilege('PRODUCT_READ'),
                new Privilege('PRODUCT_UPDATE'),
                new Privilege('PRODUCT_DELETE'),
                new Privilege('ATTRIBUTE_CREATE'),
                new Privilege('ATTRIBUTE_READ'),
                new Privilege('ATTRIBUTE_UPDATE'),
                new Privilege('ATTRIBUTE_DELETE'),
                new Privilege('ATTRIBUTE_GROUP_CREATE'),
                new Privilege('ATTRIBUTE_GROUP_READ'),
                new Privilege('ATTRIBUTE_GROUP_UPDATE'),
                new Privilege('ATTRIBUTE_GROUP_DELETE'),
                new Privilege('TEMPLATE_DESIGNER_CREATE'),
                new Privilege('TEMPLATE_DESIGNER_READ'),
                new Privilege('TEMPLATE_DESIGNER_UPDATE'),
                new Privilege('TEMPLATE_DESIGNER_DELETE'),
                new Privilege('MULTIMEDIA_CREATE'),
                new Privilege('MULTIMEDIA_READ'),
                new Privilege('MULTIMEDIA_UPDATE'),
                new Privilege('MULTIMEDIA_DELETE'),
                new Privilege('PRODUCT_COLLECTION_CREATE'),
                new Privilege('PRODUCT_COLLECTION_UPDATE'),
                new Privilege('PRODUCT_COLLECTION_READ'),
                new Privilege('PRODUCT_COLLECTION_DELETE'),
            ]
        );

        $this->commandBus->dispatch($command);
    }
}
