<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterShopware6\Tests\Infrastructure\Model;

use Ergonode\ExporterShopware6\Infrastructure\Model\Shopware6Media;
use PHPUnit\Framework\TestCase;

class Shopware6MediaTest extends TestCase
{
    /**
     * @var string
     */
    private string $id;

    protected function setUp(): void
    {
        $this->id = 'any_id';
    }

    public function testCreateModel(): void
    {
        $model = new Shopware6Media($this->id);

        self::assertEquals($this->id, $model->getId());
    }
}
