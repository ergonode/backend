<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Multimedia\Tests\Domain\ValueObject;

use Ergonode\Multimedia\Domain\ValueObject\Hash;
use PHPUnit\Framework\TestCase;

/**
 */
class HashTest extends TestCase
{
    /**
     * @param string $data
     *
     * @dataProvider validDataProvider
     */
    public function testValidHashCreation(string $data): void
    {
        $hash = new Hash($data);

        $this->assertSame($data, $hash->getValue());
        $this->assertSame($data, (string) $hash);
    }

    /**
     * @param string $data
     *
     * @dataProvider validDataProvider
     */
    public function testValidDataValidationMethod(string $data): void
    {
        $this->assertTrue(Hash::isValid($data));
    }

    /**
     *
     * @param string $data
     *
     * @dataProvider invalidDataProvider
     */
    public function testNotValidHashCreation(string $data): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new Hash($data);
    }

    /**
     * @param string $data
     *
     * @dataProvider invalidDataProvider
     */
    public function testInvalidDataValidationMethod(string $data): void
    {
        $this->assertFalse(Hash::isValid($data));
    }

    /**
     */
    public function testObjectEquality(): void
    {
        $object1 = new Hash('da39a3ee5e6b4b0d3255bfef95601890afd80709');
        $object2 = new Hash('da39a3ee5e6b4b0d3255bfef95601890afd80709');
        $object3 = new Hash('3fcaf44b_b65a_48bb_8769_49d1a8fa48d0');
        $this->assertTrue($object1->isEqual($object2));
        $this->assertTrue($object2->isEqual($object1));
        $this->assertFalse($object1->isEqual($object3));
        $this->assertFalse($object2->isEqual($object3));
        $this->assertFalse($object3->isEqual($object1));
        $this->assertFalse($object3->isEqual($object2));
    }


    /**
     * @return array
     */
    public function validDataProvider(): array
    {
        return [
            ['da39a3ee5e6b4b0d3255bfef95601890afd80709'],
            ['3fcaf44b-b65a-48bb-8769-49d1a8fa48d0'],
            ['3fcaf44b_b65a_48bb_8769_49d1a8fa48d0'],
            [str_repeat('x', 32)],
            [str_repeat('x', 64)],
        ];
    }

    /**
     * @return array
     */
    public function invalidDataProvider(): array
    {
        return [
            ['(^*$(#(*$!)('],
            ['\n'],
            [' da39a3ee5e6b4b0d3255bfef95601890afd80709 '],
            ['da39a3ee5e6b4b0d3255bfef95601890afd8070!'],
            [str_repeat('x', 31)],
            [str_repeat('x', 65)],
        ];
    }
}
