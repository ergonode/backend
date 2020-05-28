<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Exporter\Tests\Domain\Command\Export;

use Ergonode\Exporter\Domain\Command\Export\ProcessExportCommand;
use PHPUnit\Framework\TestCase;
use Ergonode\SharedKernel\Domain\Aggregate\ExportId;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;

/**
 */
class ProcessExportCommandTest extends TestCase
{
    /**
     */
    public function testCommandCreation(): void
    {
        $exportId = $this->createMock(ExportId::class);
        $productId = $this->createMock(ProductId::class);

        $command = new ProcessExportCommand($exportId, $productId);
        $this->assertSame($exportId, $command->getExportId());
        $this->assertSame($productId, $command->getProductId());
    }
}
