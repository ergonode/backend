<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 *
 */

declare(strict_types = 1);

namespace Ergonode\Exporter\Tests\Domain\Command\ExportProfile;

use Ergonode\Exporter\Domain\Command\ExportProfile\CreateExportProfileCommand;
use Ergonode\SharedKernel\Domain\Aggregate\ExportProfileId;
use PHPUnit\Framework\TestCase;

/**
 */
class CreateExportProfileCommandTest extends TestCase
{
    /**
     */
    public function testCommand()
    {
        $name = 'any name';
        $type = 'supported_type';
        $parameters = [];

        $command = new CreateExportProfileCommand($name, $type, $parameters);

        $this->assertSame($name, $command->getName());
        $this->assertSame($type, $command->getType());
        $this->assertSame($parameters, $command->getParameters());
        $this->assertTrue(ExportProfileId::isValid($command->getId()->getValue()));
    }
}
