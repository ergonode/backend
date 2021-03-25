<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Tests\Infrastructure\Model\Product;

use Ergonode\ExporterShopware6\Infrastructure\Model\Product\Shopware6ProductConfiguratorSettings;
use PHPUnit\Framework\TestCase;

class Shopware6ProductConfiguratorSettingsTest extends TestCase
{
    private string $id;

    private string $optionId;

    private string $json;

    protected function setUp(): void
    {
        $this->id = 'any_id';
        $this->optionId = 'any_option_id';
        $this->json = '{"id":"any_id","optionId":"any_option_id"}';
    }

    public function testCreateModel(): void
    {
        $model = new Shopware6ProductConfiguratorSettings($this->id, $this->optionId);

        self::assertEquals($this->id, $model->getId());
        self::assertEquals($this->optionId, $model->getOptionId());
    }

    public function testSetModel(): void
    {
        $model = new Shopware6ProductConfiguratorSettings();

        $model->setOptionId($this->optionId);

        self::assertEquals($this->optionId, $model->getOptionId());
    }

    public function testJSON(): void
    {
        $model = new Shopware6ProductConfiguratorSettings($this->id, $this->optionId);

        self::assertEquals($this->json, json_encode($model->jsonSerialize(), JSON_THROW_ON_ERROR));
    }
}
