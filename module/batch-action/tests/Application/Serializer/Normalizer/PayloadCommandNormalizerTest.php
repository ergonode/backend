<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\BatchAction\Tests\Application\Serializer\Normalizer;

use Ergonode\BatchAction\Application\Serializer\Normalizer\PayloadCommandNormalizer;
use Ergonode\BatchAction\Domain\Command\AbstractPayloadCommand;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;

class PayloadCommandNormalizerTest extends TestCase
{
    /**
     * @var AbstractObjectNormalizer|MockObject
     */
    private $mockNormalizer;
    private PayloadCommandNormalizer $normalizer;

    protected function setUp(): void
    {
        $this->mockNormalizer = $this->createMock(AbstractObjectNormalizer::class);

        $this->normalizer = new PayloadCommandNormalizer(
            $this->mockNormalizer,
        );
    }

    public function testDenormalize(): void
    {
        $payload = new \stdClass();
        $payload->prop = 'test';
        $data = [
            'payload' => serialize($payload),
        ];
        $command = new class() extends AbstractPayloadCommand {
            public function __construct()
            {
            }
        };
        $this->mockNormalizer->method('denormalize')->willReturn($command);

        $result = $this->normalizer->denormalize($data, get_class($command));

        $this->assertSame($result, $command);
        $this->assertEquals(
            $payload,
            $result->getPayload(),
        );
    }

    public function testNormalize(): void
    {
        $payload = new \stdClass();
        $payload->prop = 'test';
        $command = new class($payload) extends AbstractPayloadCommand {
        };
        $this->mockNormalizer->method('normalize')
            ->with(
                $this->callback(fn ($input) => null === $input->getPayload() && $input !== $command),
                null,
                [],
            )
            ->willReturn([]);

        $result = $this->normalizer->normalize($command);

        $this->assertEquals(
            [
                'payload' => serialize($payload),
            ],
            $result,
        );
    }
}
