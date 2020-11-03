<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\BatchAction\Tests\Infrastructure\Persistence\Repository\Factory;

use Ergonode\BatchAction\Infrastructure\Persistence\Repository\Factory\DbalBatchActionFactory;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class DbalBatchActionFactoryTest extends TestCase
{
    public function testCreation(): void
    {
        $record['id'] = Uuid::uuid4()->toString();
        $record['resource_type'] = 'test resource_type';
        $record['action'] = 'test action';

        $factory = new DbalBatchActionFactory();
        $result = $factory->create($record);

        $this::assertEquals($record['id'], $result->getId()->getValue());
        $this::assertEquals($record['resource_type'], $result->getType()->getValue());
        $this::assertEquals($record['action'], $result->getAction()->getValue());
    }
}
