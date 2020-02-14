<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Exporter\Tests\Domain\Command\ExportProfile;

use Ergonode\Exporter\Domain\Command\ExportProfile\UpdateExportProfileCommand;
use Ergonode\SharedKernel\Domain\Aggregate\ExportProfileId;
use PHPUnit\Framework\TestCase;

/**
 */
class UpdateExportProfileCommandTest extends TestCase
{
    /**
     */
    public function testCommand()
    {
        $id = ExportProfileId::generate();
        $name = 'any name';
        $type = 'supported_type';
        $parameters = [];

        $command = new UpdateExportProfileCommand($id, $name, $type, $parameters);

        $this->assertSame($name, $command->getName());
        $this->assertSame($type, $command->getType());
        $this->assertSame($parameters, $command->getParameters());
        $this->assertSame($id, $command->getId());
        $this->assertTrue(ExportProfileId::isValid($command->getId()->getValue()));
    }
}
