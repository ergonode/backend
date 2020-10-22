<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Tests\Infrastructure\Model\Product;

use Ergonode\ExporterShopware6\Infrastructure\Model\Product\Shopware6ProductMedia;
use PHPUnit\Framework\TestCase;

class Shopware6ProductMediaTest extends TestCase
{
    /**
     * @var string
     */
    private string $id;

    /**
     * @var string
     */
    private string $mediaId;

    protected function setUp(): void
    {
        $this->id = 'any_id';
        $this->mediaId = 'any_media_id';
    }

    public function testCreateModel(): void
    {
        $model = new Shopware6ProductMedia($this->id, $this->mediaId);

        self::assertEquals($this->id, $model->getId());
        self::assertEquals($this->mediaId, $model->getMediaId());
    }

    public function testSetModel(): void
    {
        $model = new Shopware6ProductMedia();

        $model->setMediaId($this->mediaId);

        self::assertEquals($this->mediaId, $model->getMediaId());
    }
}
