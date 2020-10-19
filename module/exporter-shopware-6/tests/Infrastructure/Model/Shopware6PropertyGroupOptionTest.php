<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Tests\Infrastructure\Model;

use Ergonode\ExporterShopware6\Infrastructure\Model\Shopware6PropertyGroupOption;
use PHPUnit\Framework\TestCase;

/**
 */
class Shopware6PropertyGroupOptionTest extends TestCase
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
    private string $mediaId;

    /**
     * @var int
     */
    private int $position;

    /**
     */
    protected function setUp(): void
    {
        $this->id = 'any_id';
        $this->name = 'any_name';
        $this->mediaId = 'any_media_id';
        $this->position = 0;
    }
    /**
     */
    public function testCreateModel()
    {
        $model = new Shopware6PropertyGroupOption($this->id, $this->name, $this->mediaId, $this->position);

        self::assertEquals($this->id, $model->getId());
        self::assertEquals($this->name, $model->getName());
        self::assertEquals($this->mediaId, $model->getMediaId());
        self::assertEquals($this->position, $model->getPosition());
        self::assertIsInt($model->getPosition());
        self::assertNotTrue($model->isModified());
    }

    /**
     */
    public function testSetModel()
    {
        $model = new Shopware6PropertyGroupOption();

        $model->setName($this->name);
        $model->setMediaId($this->mediaId);
        $model->setPosition($this->position);

        self::assertEquals($this->name, $model->getName());
        self::assertEquals($this->mediaId, $model->getMediaId());
        self::assertEquals($this->position, $model->getPosition());
        self::assertIsInt($model->getPosition());
        self::assertTrue($model->isModified());
    }
}
