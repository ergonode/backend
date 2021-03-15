<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Multimedia\Tests\Domain\Command;

use Ergonode\Multimedia\Domain\Command\UpdateMultimediaCommand;
use PHPUnit\Framework\TestCase;
use Ergonode\SharedKernel\Domain\Aggregate\MultimediaId;
use Ergonode\Core\Domain\ValueObject\TranslatableString;

class UpdateMultimediaCommandTest extends TestCase
{
    /**
     * @throws \Exception
     */
    public function testMultimediaCreate(): void
    {
        $id = $this->createMock(MultimediaId::class);
        $name = 'any name';
        $alt = $this->createMock(TranslatableString::class);

        $command = new UpdateMultimediaCommand($id, $name, $alt);
        self::assertEquals($id, $command->getId());
        self::assertEquals($name, $command->getName());
        self::assertEquals($alt, $command->getAlt());
    }
}
