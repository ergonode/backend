<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterFile\Tests\Infrastructure\Builder\Command;

use Ergonode\ExporterFile\Infrastructure\Builder\Command\CreateFileExportChannelCommandBuilder;
use PHPUnit\Framework\TestCase;
use Ergonode\ExporterFile\Domain\Entity\FileExportChannel;
use Symfony\Component\Form\FormInterface;
use Ergonode\ExporterFile\Application\Model\ExporterFileConfigurationModel;
use Ergonode\ExporterFile\Domain\Command\CreateFileExportChannelCommand;

class CreateFileExportChannelCommandBuilderTest extends TestCase
{
    public function testSupport(): void
    {
        $builder = new CreateFileExportChannelCommandBuilder();
        self::assertTrue($builder->supported(FileExportChannel::TYPE));
        self::assertFalse($builder->supported('any other'));
    }

    public function testBuild(): void
    {
        $model = new ExporterFileConfigurationModel();
        $model->name = 'name';
        $model->format = 'format';
        $model->exportType = FileExportChannel::EXPORT_FULL;
        $form = $this->createMock(FormInterface::class);
        $form->method('getData')->willReturn($model);

        $builder = new CreateFileExportChannelCommandBuilder();
        /** @var CreateFileExportChannelCommand $result */
        $result = $builder->build($form);
        self::assertInstanceOf(CreateFileExportChannelCommand::class, $result);
        self::assertEquals($model->name, $result->getName());
        self::assertEquals($model->exportType, $result->getExportType());
        self::assertEquals($model->format, $result->getFormat());
    }
}
