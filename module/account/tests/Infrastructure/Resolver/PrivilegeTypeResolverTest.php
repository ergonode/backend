<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Tests\Infrastructure\Resolver;

use Ergonode\Account\Domain\ValueObject\Privilege;
use Ergonode\Account\Infrastructure\Resolver\PrivilegeTypeResolver;
use PHPUnit\Framework\TestCase;

/**
 */
class PrivilegeTypeResolverTest extends TestCase
{
    /**
     * @param string $input
     * @param string $expectedResult
     *
     * @dataProvider resolverDataProvider
     */
    public function testResolver(string $input, string $expectedResult): void
    {
        $result = (new PrivilegeTypeResolver())->resolve(new Privilege($input));

        $this->assertSame($expectedResult, $result);
    }

    /**
     * @return array
     */
    public function resolverDataProvider(): array
    {
        return [
            ['PRIVILEGE_TEST', 'test'],
            ['privilege_test', 'test'],
            ['PRIVILEGE_TEST_VALUE', 'value'],
        ];
    }

    /**
     */
    public function testIncorrectPrivilege(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        (new PrivilegeTypeResolver())->resolve(new Privilege('A'));
    }
}
