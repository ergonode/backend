<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Tests\Domain\Command\Status;

use Ergonode\Core\Domain\ValueObject\Color;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\Workflow\Domain\Command\Status\CreateStatusCommand;
use PHPUnit\Framework\TestCase;

/**
 */
class CreateStatusCommandTest extends TestCase
{
    /**
     * @throws \Exception
     */
    public function testCommandCreating(): void
    {
        $code = 'Any code';
        /** @var Color $color */
        $color = $this->createMock(Color::class);
        /** @var TranslatableString $name */
        $name = $this->createMock(TranslatableString::class);
        /** @var TranslatableString $description */
        $description = $this->createMock(TranslatableString::class);

        $command = new CreateStatusCommand($code, $color, $name, $description);
        $this->assertSame($code, $command->getCode());
        $this->assertSame($color, $command->getColor());
        $this->assertSame($name, $command->getName());
        $this->assertSame($description, $command->getDescription());
        $this->assertNotNull($command->getId());
    }
}
