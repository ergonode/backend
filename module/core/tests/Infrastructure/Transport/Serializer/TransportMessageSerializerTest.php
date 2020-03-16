<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Tests\Core\Infrastructure\Transport\Serializer;

use Ergonode\Core\Infrastructure\Transport\Serializer\TransportMessageSerializer;
use JMS\Serializer\SerializerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\Envelope;

/**
 */
class TransportMessageSerializerTest extends TestCase
{

    /**
     * @var MockObject|SerializerInterface
     */
    private $serializer;

    /**
     * @var string
     */
    private string $format;

    /**
     * @var TransportMessageSerializer
     */
    private TransportMessageSerializer $messageSerializer;

    /**
     */
    protected function setUp(): void
    {
        $this->serializer = $this->createMock(SerializerInterface::class);
        $this->format = 'json';
        $this->messageSerializer = new TransportMessageSerializer($this->serializer, $this->format);
    }

    /**
     *
     */
    public function testNoBodyDecode()
    {
        $this->expectExceptionMessage('Encoded envelope should have at least a `body` and some `headers`.');
        $this->expectException(\InvalidArgumentException::class);
        $this->messageSerializer->decode(['body' => 'example', 'headers' => '']);
    }

    /**
     *
     */
    public function testNoTypeDecode()
    {
        $this->expectExceptionMessage('Encoded envelope does not have a `type` header.');
        $this->expectException(\InvalidArgumentException::class);
        $this->messageSerializer->decode(['body' => 'example1', 'headers' => 'example2']);
    }

    /**
     */
    public function testDecode()
    {
        $this
            ->serializer
            ->expects($this->once())
            ->method('deserialize')->willReturn($this->createMock(\stdClass::class));
        $result = $this->messageSerializer->decode(['body' => 'example1', 'headers' => ['type' => 'example']]);
        $this->assertInstanceOf(Envelope::class, $result);
    }

    /**
     */
    public function testStampDecode()
    {
        $this->serializer->expects($this->at(0))->method('deserialize')->willReturn([]);
        $this
            ->serializer
            ->expects($this->at(1))
            ->method('deserialize')
            ->willReturn($this->createMock(\stdClass::class));
        $result = $this->messageSerializer->decode([
            'body' => 'example1',
            'headers' => [
                'type' => 'example',
                'X-Message-Stamp-test' => 'example',
            ],
        ]);
        $this->assertInstanceOf(Envelope::class, $result);
    }

    /**
     */
    public function testEncode()
    {
        $this->serializer->expects($this->once())->method('serialize')->willReturn('message');
        $message = $this->createMock(\stdClass::class);
        $envelope = new Envelope($message);
        $result = $this->messageSerializer->encode($envelope);
        $this->assertEquals('message', $result['body']);
        $this->assertArrayHasKey('headers', $result);
        $this->assertArrayHasKey('type', $result['headers']);
    }

    /**
     */
    public function testStampsEncode()
    {
        $this->serializer->expects($this->at(0))->method('serialize')->willReturn('message');
        $this->serializer->expects($this->at(1))->method('serialize')->willReturn('stamp');
        $message = $this->createMock(\stdClass::class);
        $stamp = $this->createMock(\stdClass::class);
        $envelope = new Envelope($message, [$stamp]);
        $result = $this->messageSerializer->encode($envelope);
        $this->assertEquals('stamp', $result['body']);
        $this->assertArrayHasKey('headers', $result);
        $this->assertArrayHasKey('type', $result['headers']);
    }
}
