<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Tests\Infrastructure\Model;

use Ergonode\ExporterShopware6\Infrastructure\Model\Shopware6Category;
use PHPUnit\Framework\TestCase;

class Shopware6CategoryTest extends TestCase
{
    private string $id;

    private string $name;

    private string $parentId;

    private bool $active;

    private bool $visible;

    protected function setUp(): void
    {
        $this->id = 'any_id';
        $this->name = 'any_name';
        $this->parentId = 'any_parent_id';
        $this->active = false;
        $this->visible = false;
    }

    public function testCreateModel(): void
    {
        $model = new Shopware6Category($this->id, $this->name, $this->parentId, $this->active, $this->visible);

        self::assertEquals($this->id, $model->getId());
        self::assertEquals($this->name, $model->getName());
        self::assertEquals($this->parentId, $model->getParentId());
        self::assertEquals($this->active, $model->isActive());
        self::assertEquals($this->visible, $model->isVisible());
        self::assertNotTrue($model->isModified());
    }

    public function testSetModel(): void
    {
        $model = new Shopware6Category();

        $model->setName($this->name);
        $model->setParentId($this->parentId);
        $model->setActive($this->active);
        $model->setVisible($this->visible);

        self::assertEquals($this->name, $model->getName());
        self::assertEquals($this->parentId, $model->getParentId());
        self::assertEquals($this->active, $model->isActive());
        self::assertEquals($this->visible, $model->isVisible());
        self::assertTrue($model->isModified());
    }
}
