<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterFile\Tests\Application\Form;

use Ergonode\ExporterFile\Application\Model\ExporterFileConfigurationModel;
use Symfony\Component\Form\Test\TypeTestCase;
use Ergonode\ExporterFile\Application\Form\ExporterFileConfigurationForm;
use Symfony\Component\Form\PreloadedExtension;
use Ergonode\ExporterFile\Infrastructure\Dictionary\WriterTypeDictionary;
use Ergonode\Core\Domain\Query\LanguageQueryInterface;
use PHPUnit\Framework\MockObject\MockObject;

class ExportFileChannelFormFactoryTest extends TypeTestCase
{
    /**
     * @var WriterTypeDictionary|MockObject
     */
    private WriterTypeDictionary $dictionary;

    /**
     * @var LanguageQueryInterface|MockObject
     */
    private LanguageQueryInterface $query;
    public function setUp(): void
    {
        $this->dictionary = $this->createMock(WriterTypeDictionary::class);
        $this->dictionary->method('dictionary')->willReturn(['Any format']);
        $this->query = $this->createMock(LanguageQueryInterface::class);
        $this->query->method('getDictionaryActive')->willReturn(['language']);

        parent::setUp();
    }

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

        self::assertTrue($form->isSynchronized());
        self::assertTrue($form->isValid());
        self::assertEquals($object, $objectToCompare);

        $view = $form->createView();
        $children = $view->children;

        foreach (array_keys($formData) as $key) {
            self::assertArrayHasKey($key, $children);
        }
    }

    /**
     * @return array|PreloadedExtension[]
     */
    protected function getExtensions(): array
    {
        $types[] = new ExporterFileConfigurationForm($this->dictionary, $this->query);

        return [
            new PreloadedExtension($types, []),
        ];
    }
}
