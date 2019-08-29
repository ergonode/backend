<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Tests\Domain\Command\Status;

use Ergonode\Core\Domain\ValueObject\Color;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\Workflow\Domain\Command\Status\UpdateStatusCommand;
use Ergonode\Workflow\Domain\Entity\StatusId;
use PHPUnit\Framework\TestCase;

/**
 */
class UpdateStatusCommandTest extends TestCase
{
    /**
     * @throws \Exception
     */
    public function testCommandCreating(): void
    {
        /** @var StatusId $id */
        $id = $this->createMock(StatusId::class);
        /** @var Color $color */
        $color = $this->createMock(Color::class);
        /** @var TranslatableString $name */
        $name = $this->createMock(TranslatableString::class);
        /** @var TranslatableString $description */
        $description = $this->createMock(TranslatableString::class);

        $command = new UpdateStatusCommand($id,  $color, $name, $description);
        $this->assertSame($id, $command->getId());
        $this->assertSame($color, $command->getColor());
        $this->assertSame($name, $command->getName());
        $this->assertSame($description, $command->getDescription());
    }
}
