<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterFile\Tests\Infrastructure\Builder\Command;

use Ergonode\ExporterFile\Infrastructure\Builder\Command\CreateFileExportProfileCommandBuilder;
use PHPUnit\Framework\TestCase;
use Ergonode\ExporterFile\Domain\Entity\FileExportProfile;
use Symfony\Component\Form\FormInterface;
use Ergonode\ExporterFile\Application\Model\ExporterFileConfigurationModel;
use Ergonode\ExporterFile\Domain\Command\CreateFileExportProfileCommand;

/**
 */
class CreateFileExportProfileCommandBuilderTest extends TestCase
{
    /**
     */
    public function testSupport(): void
    {
        $builder = new CreateFileExportProfileCommandBuilder();
        $this->assertTrue($builder->supported(FileExportProfile::TYPE));
        $this->assertFalse($builder->supported('any other'));
    }

    /**
     */
    public function testBuild(): void
    {
        $model = new ExporterFileConfigurationModel();
        $model->name = 'name';
        $model->format = 'format';
        $form = $this->createMock(FormInterface::class);
        $form->method('getData')->willReturn($model);

        $builder = new CreateFileExportProfileCommandBuilder();
        /** @var CreateFileExportProfileCommand $result */
        $result = $builder->build($form);
        $this->assertInstanceOf(CreateFileExportProfileCommand::class, $result);
        $this->assertEquals($model->name, $result->getName());
        $this->assertEquals($model->format, $result->getFormat());
    }
}
