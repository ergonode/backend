<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterFile\Tests\Infrastructure\Processor\Process;

use Ergonode\ExporterFile\Infrastructure\Processor\Process\ProcessFileExportProcess;
use PHPUnit\Framework\TestCase;
use Ergonode\ExporterFile\Infrastructure\Provider\WriterProvider;
use Ergonode\Attribute\Domain\Query\AttributeQueryInterface;
use Ergonode\ExporterFile\Infrastructure\Storage\FileStorage;
use Ergonode\ExporterFile\Domain\Entity\FileExportProfile;
use Ergonode\Core\Domain\Query\LanguageQueryInterface;
use Ergonode\Product\Domain\Entity\AbstractProduct;
use Ergonode\SharedKernel\Domain\Aggregate\ExportId;

/**
 */
class ProcessFileExportProcessTest extends TestCase
{
    /**
     */
    public function testProcess(): void
    {
        $id = $this->createMock(ExportId::class);
        $provider = $this->createMock(WriterProvider::class);
        $attributeQuery = $this->createMock(AttributeQueryInterface::class);
        $attributeQuery->expects($this->once())->method('getDictionary');
        $languageQuery = $this->createMock(LanguageQueryInterface::class);
        $storage = $this->createMock(FileStorage::class);
        $storage->expects($this->once())->method('open');
        $storage->expects($this->once())->method('append');
        $storage->expects($this->once())->method('close');
        $profile = $this->createMock(FileExportProfile::class);
        $product = $this->createMock(AbstractProduct::class);

        $processor = new ProcessFileExportProcess($attributeQuery, $languageQuery, $provider, $storage);
        $processor->process($id, $profile, $product);
    }
}
