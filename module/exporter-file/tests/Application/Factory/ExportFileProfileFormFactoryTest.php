<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\ExporterFile\Tests\Application\Factory;

use Ergonode\ExporterFile\Application\Model\ExporterFileConfigurationModel;
use Symfony\Component\Form\Test\TypeTestCase;
use Ergonode\ExporterFile\Application\Form\ExporterFileConfigurationForm;
use Symfony\Component\Form\PreloadedExtension;
use Ergonode\ExporterFile\Infrastructure\Dictionary\WriterTypeDictionary;

/**
 */
class ExportFileProfileFormFactoryTest extends TypeTestCase
{
    /**
     * @var WriterTypeDictionary
     */
    private WriterTypeDictionary $dictionary;

    /**
     */
    public function setUp(): void
    {
        $this->dictionary = $this->createMock(WriterTypeDictionary::class);
        $this->dictionary->method('dictionary')->willReturn(['Any format']);

        parent::setUp();
    }

    /**
     */
    public function testSubmitValidData(): void
    {
        $formData = [
            'name' => 'Any name',
            'format' => 'Any format',
        ];

        $object = new ExporterFileConfigurationModel();
        $object->name = 'Any name';
        $object->format = 'Any format';

        $objectToCompare = new ExporterFileConfigurationModel();
        $form = $this->factory->create(ExporterFileConfigurationForm::class, $objectToCompare);
        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertTrue($form->isValid());
        $this->assertEquals($object, $objectToCompare);

        $view = $form->createView();
        $children = $view->children;

        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }

    /**
     * @return array|PreloadedExtension[]
     */
    protected function getExtensions(): array
    {
        $types[] = new ExporterFileConfigurationForm($this->dictionary);

        return [
            new PreloadedExtension($types, []),
        ];
    }
}
