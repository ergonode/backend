<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Tests\Infrastructure\Model\Basic;

use Ergonode\ExporterShopware6\Infrastructure\Model\Basic\Shopware6CustomFieldConfig;
use PHPUnit\Framework\TestCase;

class Shopware6CustomFieldConfigTest extends TestCase
{
    private string $type;

    private string $customFieldType;

    private array $label;

    private string $componentName;

    private string $dateType;

    private string $numberType;

    private array $option;


    protected function setUp(): void
    {
        $this->type = 'any_type';
        $this->customFieldType = 'any_customFieldType';
        $this->label = [];
        $this->componentName = 'any_componentName';
        $this->dateType = 'any_dateType';
        $this->numberType = 'any_numberType';
        $this->option = [];
    }

    public function testCreateModel(): void
    {
        $model = new Shopware6CustomFieldConfig(
            $this->type,
            $this->customFieldType,
            $this->label,
            $this->componentName,
            $this->dateType,
            $this->numberType,
            $this->option
        );

        self::assertEquals($this->type, $model->getType());
        self::assertEquals($this->customFieldType, $model->getCustomFieldType());
        self::assertEquals($this->label, $model->getLabel());
        self::assertEquals($this->componentName, $model->getComponentName());
        self::assertEquals($this->dateType, $model->getDateType());
        self::assertEquals($this->numberType, $model->getNumberType());
        self::assertEquals($this->option, $model->getOptions());
        self::assertNotTrue($model->isModified());
    }

    public function testSetModel(): void
    {
        $model = new Shopware6CustomFieldConfig();

        $model->setType($this->type);
        $model->setCustomFieldType($this->customFieldType);
        $model->mergeLabel($this->label);
        $model->setComponentName($this->componentName);
        $model->setDateType($this->dateType);
        $model->setNumberType($this->numberType);
        $model->addOptions($this->option);

        self::assertEquals($this->type, $model->getType());
        self::assertEquals($this->customFieldType, $model->getCustomFieldType());
        self::assertEquals($this->label, $model->getLabel());
        self::assertEquals($this->componentName, $model->getComponentName());
        self::assertEquals($this->dateType, $model->getDateType());
        self::assertEquals($this->numberType, $model->getNumberType());
        self::assertEquals([$this->option], $model->getOptions());
        self::assertTrue($model->isModified());
    }
}
