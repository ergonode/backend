<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Tests\Infrastructure\Model;

use Ergonode\ExporterShopware6\Infrastructure\Model\Shopware6Media;
use PHPUnit\Framework\TestCase;

class Shopware6MediaTest extends TestCase
{
    private string $id;
    private string $fileName;

    protected function setUp(): void
    {
        $this->id = 'any_id';
        $this->fileName = 'any_file_name';
    }

    public function testCreateModel(): void
    {
        $model = new Shopware6Media($this->id, $this->fileName);

        self::assertEquals($this->id, $model->getId());
        self::assertEquals($this->fileName, $model->getFileName());
    }

    public function testSetModel(): void
    {
        $model = new Shopware6Media($this->id, null);

        $model->setFileName($this->fileName);

        self::assertEquals($this->fileName, $model->getFileName());
    }
}
