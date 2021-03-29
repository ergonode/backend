<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Tests\Infrastructure\Model;

use Ergonode\ExporterShopware6\Infrastructure\Model\AbstractShopware6CustomFieldConfig;
use PHPUnit\Framework\TestCase;

class AbstractShopware6CustomFieldConfigTest extends TestCase
{
    private string $type;

    private string $customFieldType;

    private array $label;

    private string $componentName;

    private string $json;

    protected function setUp(): void
    {
        $this->type = 'any_type';
        $this->customFieldType = 'any_customFieldType';
        $this->label = [];
        $this->componentName = 'any_componentName';
        $this->json = '{"type":"any_type","customFieldType":"any_customFieldType","componentName":"any_componentName"}';
    }

    public function testCreateModel(): void
    {
        $model = $this->getClass();

        self::assertEquals($this->type, $model->getType());
        self::assertEquals($this->customFieldType, $model->getCustomFieldType());
        self::assertEquals($this->label, $model->getLabel());
        self::assertEquals($this->componentName, $model->getComponentName());
        self::assertNotTrue($model->isModified());
    }

    public function testSetModel(): void
    {
        $model = $this->getClass();

        $model->setType($this->type);
        $model->setCustomFieldType($this->customFieldType);
        $model->mergeLabel($this->label);
        $model->setComponentName($this->componentName);


        self::assertEquals($this->type, $model->getType());
        self::assertEquals($this->customFieldType, $model->getCustomFieldType());
        self::assertEquals($this->label, $model->getLabel());
        self::assertEquals($this->componentName, $model->getComponentName());
        self::assertTrue($model->isModified());
    }

    public function testJSON(): void
    {
        $model = $this->getClass();

        self::assertEquals($this->json, json_encode($model->jsonSerialize(), JSON_THROW_ON_ERROR));
    }

    private function getClass(): AbstractShopware6CustomFieldConfig
    {
        return new class(
            $this->type,
            $this->customFieldType,
            $this->label,
            $this->componentName
        ) extends AbstractShopware6CustomFieldConfig {
        };
    }
}
