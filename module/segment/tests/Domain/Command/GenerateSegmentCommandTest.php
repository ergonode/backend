<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Segment\Tests\Domain\Command;

use Ergonode\Segment\Domain\Command\GenerateSegmentCommand;
use PHPUnit\Framework\TestCase;

/**
 */
class GenerateSegmentCommandTest extends TestCase
{
    /**
     */
    public function testCommandCreation(): void
    {
        $code = 'code';
        $type = 'type';

        $command = new GenerateSegmentCommand($code, $type);

        self::assertNotNull($command->getId());
        self::assertSame($code, $command->getCode());
        self::assertSame($type, $command->getType());
    }
}
