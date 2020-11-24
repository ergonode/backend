<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Tests\Infrastructure\Model;

use Ergonode\ExporterShopware6\Infrastructure\Model\Basic\Shopware6CustomField;
use Ergonode\ExporterShopware6\Infrastructure\Model\Basic\Shopware6CustomFieldConfig;
use PHPUnit\Framework\TestCase;

class Shopware6CustomFieldTest extends TestCase
{
    private string $id;

    private string $name;

    private string $type;

    private Shopware6CustomFieldConfig $config;

    private string $customFieldSetId;

    protected function setUp(): void
    {
        $this->id = 'any_id';
        $this->name = 'any_name';
        $this->type = 'text';
        $this->config = new Shopware6CustomFieldConfig();
        $this->customFieldSetId = 'any_set_id';
    }

    public function testCreateModel(): void
    {
        $model = new Shopware6CustomField($this->id, $this->name, $this->type, $this->config, $this->customFieldSetId);

        self::assertEquals($this->id, $model->getId());
        self::assertEquals($this->name, $model->getName());
        self::assertEquals($this->type, $model->getType());
        self::assertEquals($this->config, $model->getConfig());
        self::assertEquals($this->customFieldSetId, $model->getCustomFieldSetId());

        self::assertInstanceOf(Shopware6CustomFieldConfig::class, $model->getConfig());
        self::assertNotTrue($model->isModified());
    }

    public function testSetModel(): void
    {
        $model = new Shopware6CustomField();

        $model->setName($this->name);
        $model->setType($this->type);
        $model->setCustomFieldSetId($this->customFieldSetId);

        self::assertEquals($this->name, $model->getName());
        self::assertEquals($this->type, $model->getType());
        self::assertEquals($this->config, $model->getConfig());
        self::assertEquals($this->customFieldSetId, $model->getCustomFieldSetId());

        self::assertInstanceOf(Shopware6CustomFieldConfig::class, $model->getConfig());
        self::assertTrue($model->isModified());
    }
}
