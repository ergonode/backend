<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Tests\Domain\Command\Export;

use Ergonode\ExporterShopware6\Domain\Command\Export\PropertyGroupExportCommand;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\SharedKernel\Domain\Aggregate\ExportId;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class PropertyGroupExportCommandTest extends TestCase
{
    /**
     * @var ExportId|MockObject
     */
    private ExportId $exportId;

    /**
     * @var AttributeId|MockObject
     */
    private AttributeId $attributeId;

    protected function setUp(): void
    {
        $this->exportId = $this->createMock(ExportId::class);
        $this->attributeId = $this->createMock(AttributeId::class);
    }

    public function testCreateCommand(): void
    {
        $command = new PropertyGroupExportCommand(
            $this->exportId,
            $this->attributeId
        );

        self::assertEquals($this->exportId, $command->getExportId());
        self::assertEquals($this->attributeId, $command->getAttributeId());
    }
}
