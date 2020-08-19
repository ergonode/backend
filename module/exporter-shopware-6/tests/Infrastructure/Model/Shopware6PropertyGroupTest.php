<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Tests\Infrastructure\Model;

use Ergonode\ExporterShopware6\Infrastructure\Model\Shopware6PropertyGroup;
use PHPUnit\Framework\TestCase;

/**
 */
class Shopware6PropertyGroupTest extends TestCase
{
    /**
     * @var string
     */
    private string $id;

    /**
     * @var string
     */
    private string $name;

    /**
     * @var string
     */
    private string $displayType;

    /**
     * @var string
     */
    private string $sortingType;

    /**
     */
    protected function setUp(): void
    {
        $this->id = 'any_id';
        $this->name = 'any_name';
        $this->displayType = 'color';
        $this->sortingType = 'numeric';
    }

    /**
     */
    public function testCreateModel()
    {
        $model = new Shopware6PropertyGroup($this->id, $this->name, $this->displayType, $this->sortingType);

        $this->assertEquals($this->id, $model->getId());
        $this->assertEquals($this->name, $model->getName());
        $this->assertEquals($this->displayType, $model->getDisplayType());
        $this->assertEquals($this->sortingType, $model->getSortingType());
        $this->assertNotTrue($model->isModified());
    }

    /**
     */
    public function testSetModel()
    {
        $model = new Shopware6PropertyGroup(null, null);

        $model->setName($this->name);
        $model->setDisplayType($this->displayType);
        $model->setSortingType($this->sortingType);

        $this->assertEquals($this->name, $model->getName());
        $this->assertEquals($this->displayType, $model->getDisplayType());
        $this->assertEquals($this->sortingType, $model->getSortingType());
        $this->assertTrue($model->isModified());
    }
}
