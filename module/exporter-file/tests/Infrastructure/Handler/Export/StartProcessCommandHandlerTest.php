<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterFile\Tests\Infrastructure\Handler\Export;

use Ergonode\Channel\Domain\Entity\Export;
use Ergonode\Channel\Domain\Repository\ExportRepositoryInterface;
use Ergonode\Core\Infrastructure\Service\TempFileStorage;
use Ergonode\ExporterFile\Domain\Command\Export\StartFileExportCommand;
use Ergonode\ExporterFile\Infrastructure\Handler\Export\StartProcessCommandHandler;
use PHPUnit\Framework\TestCase;
use Ergonode\ExporterFile\Infrastructure\Builder\ExportAttributeBuilder;
use Ergonode\ExporterFile\Infrastructure\Builder\ExportProductBuilder;
use Ergonode\ExporterFile\Infrastructure\Builder\ExportTemplateElementBuilder;
use Ergonode\ExporterFile\Infrastructure\Builder\ExportOptionBuilder;
use Ergonode\ExporterFile\Infrastructure\Builder\ExportCategoryBuilder;
use Ergonode\ExporterFile\Infrastructure\Builder\ExportTemplateBuilder;

class StartProcessCommandHandlerTest extends TestCase
{
    public function testHandling(): void
    {
        $exportRepository = $this->createMock(ExportRepositoryInterface::class);
        $exportRepository->expects(self::once())->method('load')
            ->willReturn($this->createMock(Export::class));

        $storage = $this->createMock(TempFileStorage::class);

        $productBuilder = $this->createMock(ExportProductBuilder::class);
        $attributeBuilder = $this->createMock(ExportAttributeBuilder::class);
        $elementBuilder = $this->createMock(ExportTemplateElementBuilder::class);
        $templateBuilder = $this->createMock(ExportTemplateBuilder::class);
        $optionBuilder = $this->createMock(ExportOptionBuilder::class);
        $categoryBuilder = $this->createMock(ExportCategoryBuilder::class);

        $command = $this->createMock(StartFileExportCommand::class);

        $handler = new StartProcessCommandHandler(
            $exportRepository,
            $storage,
            $productBuilder,
            $elementBuilder,
            $attributeBuilder,
            $optionBuilder,
            $categoryBuilder,
            $templateBuilder
        );
        $handler->__invoke($command);
    }
}
