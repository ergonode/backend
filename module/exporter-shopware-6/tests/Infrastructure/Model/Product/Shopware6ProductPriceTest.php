<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Tests\Infrastructure\Model\Product;

use Ergonode\ExporterShopware6\Infrastructure\Model\Product\Shopware6ProductPrice;
use PHPUnit\Framework\TestCase;

class Shopware6ProductPriceTest extends TestCase
{
    private string $currencyId;

    private float $net;

    private float $gross;

    private bool $linked;

    private string $json;

    protected function setUp(): void
    {
        $this->currencyId = 'any_id';
        $this->net = 1.00;
        $this->gross = 1.23;
        $this->linked = true;
        $this->json = '{"currencyId":"any_id","net":1,"gross":1.23,"linked":true}';
    }

    public function testCreateModel(): void
    {
        $model = new Shopware6ProductPrice($this->currencyId, $this->net, $this->gross, $this->linked);

        self::assertEquals($this->currencyId, $model->getCurrencyId());
        self::assertEquals($this->net, $model->getNet());
        self::assertEquals($this->gross, $model->getGross());
        self::assertEquals($this->linked, $model->isLinked());
    }

    public function testSetModel(): void
    {
        $model = new Shopware6ProductPrice($this->currencyId, $this->net, $this->gross, $this->linked);

        $currencyId = 'any_id2';
        $net = 10.00;
        $gross = 12.30;
        $linked = false;

        $model->setCurrencyId($currencyId);
        $model->setNet($net);
        $model->setGross($gross);
        $model->setLinked($linked);

        self::assertEquals($currencyId, $model->getCurrencyId());
        self::assertEquals($net, $model->getNet());
        self::assertEquals($gross, $model->getGross());
        self::assertEquals($linked, $model->isLinked());
    }

    public function testJSON(): void
    {
        $model = new Shopware6ProductPrice($this->currencyId, $this->net, $this->gross, $this->linked);

        self::assertEquals($this->json, json_encode($model->jsonSerialize(), JSON_THROW_ON_ERROR));
    }
}
