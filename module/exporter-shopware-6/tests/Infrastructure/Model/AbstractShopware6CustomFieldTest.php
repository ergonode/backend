<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Tests\Infrastructure\Model;

use Ergonode\ExporterShopware6\Infrastructure\Model\AbstractShopware6CustomField;
use Ergonode\ExporterShopware6\Infrastructure\Model\Basic\Shopware6CustomFieldConfig;
use PHPUnit\Framework\TestCase;

class AbstractShopware6CustomFieldTest extends TestCase
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
        $model = $this->getClass();

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
        $model = $this->getClass();

        $name = 'new_name';
        $type = 'number';
        $customFieldSetId = 'new_set_id';

        $model->setName($name);
        $model->setType($type);
        $model->setCustomFieldSetId($customFieldSetId);

        self::assertEquals($name, $model->getName());
        self::assertEquals($type, $model->getType());
        self::assertEquals($customFieldSetId, $model->getCustomFieldSetId());

        self::assertInstanceOf(Shopware6CustomFieldConfig::class, $model->getConfig());
        self::assertTrue($model->isModified());
    }

    private function getClass(): AbstractShopware6CustomField
    {
        return new class(
            $this->id,
            $this->name,
            $this->type,
            $this->config,
            $this->customFieldSetId
        ) extends AbstractShopware6CustomField {
        };
    }
}
