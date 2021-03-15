<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Tests\Infrastructure\Resolver;

use Ergonode\Core\Infrastructure\Resolver\RelationshipsResolver;
use PHPUnit\Framework\TestCase;
use Ergonode\SharedKernel\Domain\AggregateId;

class RelationshipsResolverTest extends TestCase
{
    public function testResolverNoStrategies(): void
    {
        $aggregateId = $this->createMock(AggregateId::class);
        $resolver = new RelationshipsResolver();
        $result = $resolver->resolve($aggregateId);

        self::assertNull($result);
    }
}
