<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterFile\Tests\Infrastructure\Handler\Export;

use Ergonode\Attribute\Domain\Query\AttributeQueryInterface;
use Ergonode\Channel\Domain\Entity\Export;
use Ergonode\Channel\Domain\Repository\ExportRepositoryInterface;
use Ergonode\Core\Infrastructure\Service\TempFileStorage;
use Ergonode\ExporterFile\Domain\Command\Export\StartFileExportCommand;
use Ergonode\ExporterFile\Infrastructure\Handler\Export\StartProcessCommandHandler;
use PHPUnit\Framework\TestCase;

class StartProcessCommandHandlerTest extends TestCase
{
    public function testHandling(): void
    {
        $exportRepository = $this->createMock(ExportRepositoryInterface::class);
        $exportRepository->expects(self::once())->method('load')
            ->willReturn($this->createMock(Export::class));

        $storage = $this->createMock(TempFileStorage::class);

        $attributeQuery = $this->createMock(AttributeQueryInterface::class);

        $command = $this->createMock(StartFileExportCommand::class);

        $handler = new StartProcessCommandHandler($exportRepository, $storage, $attributeQuery);
        $handler->__invoke($command);
    }
}
