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
use Ergonode\Designer\Domain\Entity\TemplateElementInterface;
use Ergonode\ExporterFile\Domain\Entity\FileExportChannel;
use PHPUnit\Framework\TestCase;
use Ergonode\ExporterFile\Infrastructure\Processor\TemplateElementProcessor;

class TemplateElementProcessorTest extends TestCase
{
    private SerializerInterface $serializer;

    private Template $template;

    private TemplateElementInterface $element;

    private FileExportChannel $channel;

    protected function setUp(): void
    {
        $this->serializer = $this->createMock(SerializerInterface::class);
        $this->template = $this->createMock(Template::class);
        $this->channel = $this->createMock(FileExportChannel::class);
        $this->element = $this->createMock(TemplateElementInterface::class);
        $this->element->method('getType')->willReturn('test_type');
    }

    public function testProcessor(): void
    {
        $this->template->method('getName')->willReturn('test_name');
        $this->template->method('getElements')->willReturn(new ArrayCollection([$this->element]));

        $processor = new TemplateElementProcessor($this->serializer);
        $result = $processor->process($this->channel, $this->template, $this->element);

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
        $this->serializer->method('serialize')->willThrowException(new \Exception());

        $processor = new TemplateElementProcessor($this->serializer);
        $processor->process($this->channel, $this->template, $this->element);
    }
}
