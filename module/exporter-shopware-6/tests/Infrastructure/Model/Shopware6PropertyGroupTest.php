<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Tests\Infrastructure\Model;

use Ergonode\ExporterShopware6\Infrastructure\Model\Shopware6PropertyGroup;
use PHPUnit\Framework\TestCase;

class Shopware6PropertyGroupTest extends TestCase
{
    private string $id;

    private string $name;

    private string $displayType;

    private string $sortingType;

    private string $json;

    protected function setUp(): void
    {
        $this->id = 'any_id';
        $this->name = 'any_name';
        $this->displayType = 'color';
        $this->sortingType = 'numeric';
        $this->json = '{"name":"any_name","displayType":"color","sortingType":"numeric"}';
    }

    public function testCreateModel(): void
    {
        $model = new Shopware6PropertyGroup($this->id, $this->name, $this->displayType, $this->sortingType);

        self::assertEquals($this->id, $model->getId());
        self::assertEquals($this->name, $model->getName());
        self::assertEquals($this->displayType, $model->getDisplayType());
        self::assertEquals($this->sortingType, $model->getSortingType());
        self::assertNotTrue($model->isModified());
    }

    public function testSetModel(): void
    {
        $model = new Shopware6PropertyGroup(null, null);

        $model->setName($this->name);
        $model->setDisplayType($this->displayType);
        $model->setSortingType($this->sortingType);

        self::assertEquals($this->name, $model->getName());
        self::assertEquals($this->displayType, $model->getDisplayType());
        self::assertEquals($this->sortingType, $model->getSortingType());
        self::assertTrue($model->isModified());
    }

    public function testJSON(): void
    {
        $model = new Shopware6PropertyGroup($this->id, $this->name, $this->displayType, $this->sortingType);

        self::assertEquals($this->json, json_encode($model->jsonSerialize(), JSON_THROW_ON_ERROR));
    }
}
