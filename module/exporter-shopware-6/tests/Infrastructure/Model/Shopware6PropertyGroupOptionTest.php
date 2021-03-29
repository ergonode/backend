<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Tests\Infrastructure\Model;

use Ergonode\ExporterShopware6\Infrastructure\Model\Shopware6PropertyGroupOption;
use PHPUnit\Framework\TestCase;

class Shopware6PropertyGroupOptionTest extends TestCase
{
    private string $id;

    private string $name;

    private string $mediaId;

    private int $position;

    private string $json;

    protected function setUp(): void
    {
        $this->id = 'any_id';
        $this->name = 'any_name';
        $this->mediaId = 'any_media_id';
        $this->position = 0;
        $this->json = '{"name":"any_name","mediaId":"any_media_id","position":0}';
    }

    public function testCreateModel(): void
    {
        $model = new Shopware6PropertyGroupOption($this->id, $this->name, $this->mediaId, $this->position);

        $this->assertEquals($this->id, $model->getId());
        $this->assertEquals($this->name, $model->getName());
        $this->assertEquals($this->mediaId, $model->getMediaId());
        $this->assertEquals($this->position, $model->getPosition());
        $this->assertIsInt($model->getPosition());
        $this->assertNotTrue($model->isModified());
    }

    public function testSetModel(): void
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

    public function testJSON(): void
    {
        $model = new Shopware6PropertyGroupOption($this->id, $this->name, $this->mediaId, $this->position);

        self::assertEquals($this->json, json_encode($model->jsonSerialize(), JSON_THROW_ON_ERROR));
    }
}
