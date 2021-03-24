<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Tests\Domain\Command\Export;

use Ergonode\ExporterShopware6\Domain\Command\Export\StartShopware6ExportCommand;
use Ergonode\SharedKernel\Domain\Aggregate\ExportId;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class StartShopware6ExportCommandTest extends TestCase
{
    /**
     * @var ExportId|MockObject
     */
    private ExportId $exportId;

    protected function setUp(): void
    {
        $this->exportId = $this->createMock(ExportId::class);
    }

    public function testCreateCommand(): void
    {
        $command = new StartShopware6ExportCommand($this->exportId);

        self::assertEquals($this->exportId, $command->getExportId());
    }
}
