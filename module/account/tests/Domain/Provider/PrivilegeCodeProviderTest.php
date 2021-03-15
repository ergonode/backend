<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Tests\Domain\Provider;

use Ergonode\Account\Domain\Provider\PrivilegeCodeProvider;
use Ergonode\Account\Domain\Query\PrivilegeQueryInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class PrivilegeCodeProviderTest extends TestCase
{
    public function testProvidingPrivilegeCode(): void
    {
        /** @var PrivilegeQueryInterface | MockObject $query */
        $query = $this->createMock(PrivilegeQueryInterface::class);
        $query->method('getPrivileges')->willReturn(
            [
                [
                    'id' => 'id1',
                    'code' => 'code1',
                    'area' => 'area1',
                    'description' => 'description1',
                ],
                [
                    'id' => 'id1',
                    'code' => 'code2',
                    'area' => 'area1',
                    'description' => 'description1',
                ],
            ]
        );
        $provider = new PrivilegeCodeProvider($query);

        $this->assertSame(['code1', 'code2'], $provider->provide());
    }
}
