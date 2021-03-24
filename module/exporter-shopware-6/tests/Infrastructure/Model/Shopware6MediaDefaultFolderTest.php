<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Tests\Infrastructure\Model;

use Ergonode\ExporterShopware6\Infrastructure\Model\Shopware6MediaDefaultFolder;
use PHPUnit\Framework\TestCase;

class Shopware6MediaDefaultFolderTest extends TestCase
{
    private string $id;

    private string $entity;

    private string $mediaFolderId;

    protected function setUp(): void
    {
        $this->id = 'any_id';
        $this->entity = 'product';
        $this->mediaFolderId = 'any_folder_id';
    }

    public function testCreateModel(): void
    {
        $model = new Shopware6MediaDefaultFolder($this->id, $this->entity, $this->mediaFolderId);

        self::assertEquals($this->id, $model->getId());
        self::assertEquals($this->entity, $model->getEntity());
        self::assertEquals($this->mediaFolderId, $model->getMediaFolderId());
    }

    public function testSetModel(): void
    {
        $model = new Shopware6MediaDefaultFolder();

        $model->setEntity($this->entity);
        $model->setMediaFolderId($this->mediaFolderId);

        self::assertEquals($this->entity, $model->getEntity());
        self::assertEquals($this->mediaFolderId, $model->getMediaFolderId());
    }
}
