<?php
/*
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\ExporterFile\Tests\Infrastructure\Processor;

use Doctrine\Common\Collections\ArrayCollection;
use Ergonode\Channel\Infrastructure\Exception\ExportException;
use Ergonode\Core\Application\Serializer\SerializerInterface;
use Ergonode\Designer\Domain\Entity\Template;
use Ergonode\Designer\Domain\Entity\TemplateElement;
use Ergonode\ExporterFile\Domain\Entity\FileExportChannel;
use Ergonode\ExporterFile\Infrastructure\Processor\TemplateProcessor;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class TemplateProcessorTest extends TestCase
{
    /**
     * @var SerializerInterface|MockObject
     */
    private SerializerInterface $serializer;

    /**
     * @var Template|MockObject
     */
    private Template $template;

    private FileExportChannel $channel;

    protected function setUp(): void
    {
        $this->serializer = $this->createMock(SerializerInterface::class);
        $this->template = $this->createMock(Template::class);
        $this->channel = $this->createMock(FileExportChannel::class);
    }

    public function testProcessor(): void
    {
        $this->template->method('getName')->willReturn('test_name');

        $templateElement = $this->createMock(TemplateElement::class);
        $templateElement->method('getType')->willReturn('test_type');
        $this->template->method('getElements')->willReturn(new ArrayCollection(array_values([$templateElement])));

        $processor = new TemplateProcessor($this->serializer);
        $result = $processor->process($this->channel, $this->template);

        $languageData = $result->getLanguages()[null];

        self::assertArrayHasKey('_name', $languageData->getValues());
        self::assertArrayHasKey('_type', $languageData->getValues());
        self::assertArrayHasKey('_x', $languageData->getValues());
        self::assertArrayHasKey('_y', $languageData->getValues());
        self::assertArrayHasKey('_width', $languageData->getValues());
        self::assertArrayHasKey('_height', $languageData->getValues());
        self::assertArrayHasKey('_properties', $languageData->getValues());

        self::assertEquals('test_name', $languageData->getValues()['_name']);
        self::assertEquals('test_type', $languageData->getValues()['_type']);
    }

    public function testInvalidArgumentExceptionProcessor(): void
    {
        $this->expectException(ExportException::class);

        $processor = new TemplateProcessor($this->serializer);
        $processor->process($this->channel, $this->template);
    }
}
