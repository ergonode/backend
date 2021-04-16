<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Product\Tests\Domain\ValueObject;

use Ergonode\Product\Domain\ValueObject\Sku;
use PHPUnit\Framework\TestCase;

/**
 * Class SkuTest
 */
class SkuTest extends TestCase
{
    /**
     * @dataProvider data
     */
    public function testGetValue(string $sku): void
    {
        $sku = new Sku($sku);

        $this->assertEquals($sku, $sku->getValue());
    }

    /**
     * @dataProvider invalidData
     */
    public function testInvalidValue(string $sku): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new Sku($sku);
    }

    /**
     * @return array
     */
    public function data(): array
    {
        return [
            ['B.AD46SB/BN926'],
        ];
    }

    /**
     * @return array
     */
    public function invalidData(): array
    {
        return [
            ['yKL3rWXluEM7NapnMSVpYHpicQJkpJ0Obfx91mma0xwnQxUfsrwy5Nfki1LUZR4qYolBTlUFDO4RkeINIkjPzMfTSit0bQ
            ZJevXA6GMsj0LnSZaiT1bBfr00vtKWqLAPollonRzb6GBVlT5U9I6nC49r3Vnj2jUgpA73CvfnVnFBNnHqCaI2Cu48SKaVSRGgROho
            D1dGPvvq98okavZ3ktVHk0TcyyiyfoH52U3gP3J5bNTVZngivjPJAqtOW8TO'],
            [''],
        ];
    }
}
