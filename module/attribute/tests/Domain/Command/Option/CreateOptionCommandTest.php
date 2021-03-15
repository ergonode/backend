<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Attribute\Tests\Domain\Command\Option;

use Ergonode\Attribute\Domain\Command\Option\CreateOptionCommand;
use Ergonode\Attribute\Domain\ValueObject\OptionKey;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class CreateOptionCommandTest extends TestCase
{
    /**
     * @throws \Exception
     */
    public function testCreateCommand(): void
    {
        /** @var AttributeId|MockObject $attributeId */
        $attributeId = $this->createMock(AttributeId::class);
        /** @var OptionKey|MockObject $key */
        $key = $this->createMock(OptionKey::class);
        /** @var TranslatableString|MockObject $label */
        $label = $this->createMock(TranslatableString::class);

        $command = new CreateOptionCommand($attributeId, $key, $label);
        $this->assertNotNull($command->getId());
        $this->assertEquals($attributeId, $command->getAttributeId());
        $this->assertEquals($key, $command->getCode());
        $this->assertEquals($label, $command->getLabel());
    }
}
