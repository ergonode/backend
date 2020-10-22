<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Tests\Infrastructure\Model;

use Ergonode\ExporterShopware6\Infrastructure\Model\Shopware6PropertyGroupOption;
use PHPUnit\Framework\TestCase;

class Shopware6PropertyGroupOptionTest extends TestCase
{
    private string $id;

    private string $name;

    private string $mediaId;

    private int $position;

    protected function setUp(): void
    {
        $this->id = 'any_id';
        $this->name = 'any_name';
        $this->mediaId = 'any_media_id';
        $this->position = 0;
    }
    public function testCreateModel()
    {
        $model = new Shopware6PropertyGroupOption($this->id, $this->name, $this->mediaId, $this->position);

        $this->assertEquals($this->id, $model->getId());
        $this->assertEquals($this->name, $model->getName());
        $this->assertEquals($this->mediaId, $model->getMediaId());
        $this->assertEquals($this->position, $model->getPosition());
        $this->assertIsInt($model->getPosition());
        $this->assertNotTrue($model->isModified());
    }

    public function testSetModel()
    {
        $model = new Shopware6PropertyGroupOption();

        $model->setName($this->name);
        $model->setMediaId($this->mediaId);
        $model->setPosition($this->position);

        $this->assertEquals($this->name, $model->getName());
        $this->assertEquals($this->mediaId, $model->getMediaId());
        $this->assertEquals($this->position, $model->getPosition());
        $this->assertIsInt($model->getPosition());
        $this->assertTrue($model->isModified());
    }
}
