<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Exporter\Tests\Domain\Command\ExportProfile;

use Ergonode\Exporter\Domain\Command\ExportProfile\DeleteExportProfileCommand;
use Ergonode\SharedKernel\Domain\Aggregate\ExportProfileId;
use PHPUnit\Framework\TestCase;

/**
 */
class DeleteExportProfileCommandTest extends TestCase
{

    /**
     */
    public function testCommand()
    {
        $id = ExportProfileId::generate();

        $command = new DeleteExportProfileCommand($id);

        $this->assertSame($id, $command->getId());
        $this->assertTrue(ExportProfileId::isValid($command->getId()->getValue()));
    }
}
