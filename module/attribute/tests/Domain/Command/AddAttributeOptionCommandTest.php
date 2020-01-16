<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Attribute\Tests\Domain\Command;

use Ergonode\Attribute\Domain\Command\AddAttributeOptionCommand;
use Ergonode\Attribute\Domain\Entity\AttributeId;
use Ergonode\Attribute\Domain\ValueObject\OptionInterface;
use Ergonode\Attribute\Domain\ValueObject\OptionKey;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 */
class AddAttributeOptionCommandTest extends TestCase
{
    /**
     */
    public function testCreateCommand(): void
    {
        /** @var AttributeId | MockObject $attributeId */
        $attributeId = $this->createMock(AttributeId::class);
        /** @var OptionKey | MockObject $optionKey */
        $optionKey = $this->createMock(OptionKey::class);
        /** @var OptionInterface | MockObject $optionInterface */
        $optionInterface = $this->createMock(OptionInterface::class);

        $command = new AddAttributeOptionCommand($attributeId, $optionKey, $optionInterface);
        $this->assertEquals($attributeId, $command->getAttributeId());
        $this->assertEquals($optionKey, $command->getOptionKey());
        $this->assertEquals($optionInterface, $command->getOption());
    }
}
