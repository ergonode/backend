<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Tests\Domain\Command\Group;

use Ergonode\Attribute\Domain\Command\Group\CreateAttributeGroupCommand;
use Ergonode\Attribute\Domain\ValueObject\AttributeGroupCode;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class CreateAttributeGroupCommandTest extends TestCase
{
    /**
     * @throws \Exception
     */
    public function testCreateCommand(): void
    {
        /** @var AttributeGroupCode|MockObject $code */
        $code = $this->createMock(AttributeGroupCode::class);
        /** @var TranslatableString|MockObject $name */
        $name = $this->createMock(TranslatableString::class);

        $command = new CreateAttributeGroupCommand($code, $name);
        $this->assertNotNull($command->getId());
        $this->assertEquals($code, $command->getCode());
        $this->assertEquals($name, $command->getName());
    }
}
