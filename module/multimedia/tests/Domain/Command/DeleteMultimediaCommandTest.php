<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Multimedia\Tests\Domain\Command;

use Ergonode\Multimedia\Domain\Command\DeleteMultimediaCommand;
use Ergonode\SharedKernel\Domain\Aggregate\MultimediaId;
use PHPUnit\Framework\TestCase;

class DeleteMultimediaCommandTest extends TestCase
{
    /**
     * @throws \Exception
     */
    public function testCommand(): void
    {
        $id = MultimediaId::generate();

        $command = new DeleteMultimediaCommand($id);
        self::assertEquals($id, $command->getId());
    }
}
