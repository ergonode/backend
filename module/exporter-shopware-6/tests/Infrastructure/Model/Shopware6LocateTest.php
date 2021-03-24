<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Tests\Infrastructure\Model;

use Ergonode\ExporterShopware6\Infrastructure\Model\Shopware6Locate;
use PHPUnit\Framework\TestCase;

class Shopware6LocateTest extends TestCase
{
    private string $id;

    private string $code;

    private string $name;

    protected function setUp(): void
    {
        $this->id = 'any_id';
        $this->code = 'en-GB';
        $this->name = 'any_name';
    }

    public function testCreateModel(): void
    {
        $model = new Shopware6Locate($this->id, $this->code, $this->name);

        self::assertEquals($this->id, $model->getId());
        self::assertEquals($this->code, $model->getCode());
        self::assertEquals($this->name, $model->getName());
    }

    public function testSetModel(): void
    {
        $model = new Shopware6Locate();

        $model->setName($this->name);
        $model->setCode($this->code);

        self::assertEquals($this->code, $model->getCode());
        self::assertEquals($this->name, $model->getName());
    }
}
