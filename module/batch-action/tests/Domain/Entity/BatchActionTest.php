<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\BatchAction\Tests\Domain\Entity;

use Ergonode\BatchAction\Domain\Entity\BatchAction;
use PHPUnit\Framework\TestCase;
use Ergonode\BatchAction\Domain\Entity\BatchActionId;
use Ergonode\BatchAction\Domain\ValueObject\BatchActionType;
use Ergonode\BatchAction\Domain\ValueObject\BatchActionAction;

class BatchActionTest extends TestCase
{
    public function testEntityCreation(): void
    {
        $id = $this->createMock(BatchActionId::class);
        $type = $this->createMock(BatchActionType::class);
        $action = $this->createMock(BatchActionAction::class);

        $entity = new BatchAction($id, $type, $action);

        self::assertEquals($id, $entity->getId());
        self::assertEquals($type, $entity->getType());
        self::assertEquals($action, $entity->getAction());
    }
}
