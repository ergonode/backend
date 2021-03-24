<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterShopware6\Tests\Domain\Command\Export;

use Ergonode\Channel\Domain\ValueObject\ExportLineId;
use Ergonode\ExporterShopware6\Domain\Command\Export\CustomFieldExportCommand;
use Ergonode\SharedKernel\Domain\Aggregate\AttributeId;
use Ergonode\SharedKernel\Domain\Aggregate\ExportId;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class CustomFieldExportCommandTest extends TestCase
{
    /**
     * @var ExportLineId|MockObject
     */
    private ExportLineId $lineId;

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
        $this->lineId = $this->createMock(ExportLineId::class);
        $this->exportId = $this->createMock(ExportId::class);
        $this->attributeId = $this->createMock(AttributeId::class);
    }

    public function testCreateCommand(): void
    {
        $command = new CustomFieldExportCommand(
            $this->lineId,
            $this->exportId,
            $this->attributeId
        );

        self::assertEquals($this->lineId, $command->getLineId());
        self::assertEquals($this->exportId, $command->getExportId());
        self::assertEquals($this->attributeId, $command->getAttributeId());
    }
}
