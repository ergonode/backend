<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Category\Tests\Domain\Event;

use Ergonode\Category\Domain\Entity\CategoryId;
use Ergonode\Category\Domain\Event\CategoryCreatedEvent;
use Ergonode\Category\Domain\ValueObject\CategoryCode;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use PHPUnit\Framework\TestCase;

/**
 */
class CategoryCreatedEventTest extends TestCase
{
    /**
     */
    public function testEvent(): void
    {
        $id = $this->createMock(CategoryId::class);
        $code = $this->createMock(CategoryCode::class);
        $name = $this->createMock(TranslatableString::class);

        $command = new CategoryCreatedEvent($id, $code, $name);
        $this->assertEquals($id, $command->getId());
        $this->assertEquals($code, $command->getCode());
        $this->assertEquals($name, $command->getName());
    }
}
