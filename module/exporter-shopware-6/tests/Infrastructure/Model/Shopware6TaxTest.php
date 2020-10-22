<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Tests\Infrastructure\Model;

use Ergonode\ExporterShopware6\Infrastructure\Model\Shopware6Tax;
use PHPUnit\Framework\TestCase;

class Shopware6TaxTest extends TestCase
{
    /**
     * @var string
     */
    private string $id;

    /**
     * @var float
     */
    private float $rate;

    /**
     * @var string
     */
    private string $name;

    protected function setUp(): void
    {
        $this->id = 'any_id';
        $this->name = 'any_name';
        $this->rate = 23.3;
    }

    public function testCreateModel()
    {
        $model = new Shopware6Tax($this->id, $this->rate, $this->name);

        $this->assertEquals($this->id, $model->getId());
        $this->assertEquals($this->rate, $model->getRate());
        $this->assertIsFloat($model->getRate());
        $this->assertEquals($this->name, $model->getName());
    }

    public function testSetModel()
    {
        $model = new Shopware6Tax();

        $model->setName($this->name);
        $model->setRate($this->rate);

        $this->assertEquals($this->rate, $model->getRate());
        $this->assertIsFloat($model->getRate());
        $this->assertEquals($this->name, $model->getName());
    }
}
