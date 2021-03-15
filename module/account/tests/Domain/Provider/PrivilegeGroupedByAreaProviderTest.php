<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Tests\Domain\Provider;

use Ergonode\Account\Domain\Provider\PrivilegeGroupedByAreaProvider;
use Ergonode\Account\Domain\Query\PrivilegeQueryInterface;
use Ergonode\Account\Infrastructure\Resolver\PrivilegeTypeResolverInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class PrivilegeGroupedByAreaProviderTest extends TestCase
{
    public function testProvidingPrivilegeGroupedByArea(): void
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
            ]
        );

        /** @var PrivilegeTypeResolverInterface | MockObject $resolver */
        $resolver = $this->createMock(PrivilegeTypeResolverInterface::class);
        $resolver->method('resolve')->willReturn('type');

        $provider = new PrivilegeGroupedByAreaProvider($query, $resolver);

        $this->assertSame(['area1' => ['type' => 'code1']], $provider->provide());
    }
}
