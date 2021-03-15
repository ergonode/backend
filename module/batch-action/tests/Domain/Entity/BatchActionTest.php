<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\BatchAction\Tests\Domain\Entity;

use Ergonode\BatchAction\Domain\Entity\BatchAction;
use PHPUnit\Framework\TestCase;
use Ergonode\BatchAction\Domain\Entity\BatchActionId;
use Ergonode\BatchAction\Domain\ValueObject\BatchActionType;

class BatchActionTest extends TestCase
{
    public function testEntityCreation(): void
    {
        $id = $this->createMock(BatchActionId::class);
        $type = $this->createMock(BatchActionType::class);

        $entity = new BatchAction($id, $type);

        self::assertEquals($id, $entity->getId());
        self::assertEquals($type, $entity->getType());
    }
}
