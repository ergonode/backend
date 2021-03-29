<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Tests\Infrastructure\Model\Product;

use Ergonode\ExporterShopware6\Infrastructure\Model\Product\Shopware6ProductMedia;
use PHPUnit\Framework\TestCase;

class Shopware6ProductMediaTest extends TestCase
{
    private string $id;

    private string $mediaId;

    private int $position;

    private string $json;

    protected function setUp(): void
    {
        $this->id = 'any_id';
        $this->mediaId = 'any_media_id';
        $this->position = 0;
        $this->json = '{"id":"any_id","mediaId":"any_media_id","position":0}';
    }

    public function testCreateModel(): void
    {
        $model = new Shopware6ProductMedia($this->id, $this->mediaId, $this->position);

        self::assertEquals($this->id, $model->getId());
        self::assertEquals($this->mediaId, $model->getMediaId());
        self::assertEquals($this->position, $model->getPosition());
        self::assertGreaterThanOrEqual(0, $model->getPosition());
    }

    public function testSetModel(): void
    {
        $model = new Shopware6ProductMedia();

        $model->setMediaId($this->mediaId);
        $model->setPosition($this->position);

        self::assertEquals($this->mediaId, $model->getMediaId());
        self::assertEquals($this->position, $model->getPosition());
    }

    public function testJSON(): void
    {
        $model = new Shopware6ProductMedia($this->id, $this->mediaId, $this->position);

        self::assertEquals($this->json, json_encode($model->jsonSerialize(), JSON_THROW_ON_ERROR));
    }
}
