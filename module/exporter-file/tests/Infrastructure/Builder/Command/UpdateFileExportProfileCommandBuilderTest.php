<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterFile\Tests\Infrastructure\Builder\Command;

use Ergonode\ExporterFile\Infrastructure\Builder\Command\UpdateFileExportProfileCommandBuilder;
use PHPUnit\Framework\TestCase;
use Ergonode\ExporterFile\Domain\Entity\FileExportProfile;
use Symfony\Component\Form\FormInterface;
use Ergonode\ExporterFile\Application\Model\ExporterFileConfigurationModel;
use Ergonode\ExporterFile\Domain\Command\UpdateFileExportProfileCommand;
use Ergonode\SharedKernel\Domain\Aggregate\ExportProfileId;

/**
 */
class UpdateFileExportProfileCommandBuilderTest extends TestCase
{
    /**
     */
    public function testSupport(): void
    {
        $builder = new UpdateFileExportProfileCommandBuilder();
        $this->assertTrue($builder->supported(FileExportProfile::TYPE));
        $this->assertFalse($builder->supported('any other'));
    }

    /**
     */
    public function testBuild(): void
    {
        $id = $this->createMock(ExportProfileId::class);
        $model = new ExporterFileConfigurationModel();
        $model->name = 'name';
        $model->format = 'format';
        $form = $this->createMock(FormInterface::class);
        $form->method('getData')->willReturn($model);

        $builder = new UpdateFileExportProfileCommandBuilder();
        /** @var UpdateFileExportProfileCommand $result */
        $result = $builder->build($id, $form);
        $this->assertInstanceOf(UpdateFileExportProfileCommand::class, $result);
        $this->assertEquals($model->name, $result->getName());
        $this->assertEquals($model->format, $result->getFormat());
    }
}
