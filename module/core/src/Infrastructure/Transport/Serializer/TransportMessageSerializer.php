<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Infrastructure\Transport\Serializer;

use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Transport\Serialization\SerializerInterface as MessageSerializerInterface;
use Ergonode\Core\Application\Serializer\SerializerInterface;

class TransportMessageSerializer implements MessageSerializerInterface
{
    private const STAMP_HEADER_PREFIX = 'X-Message-Stamp-';

    private SerializerInterface $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * {@inheritdoc}
     */
    public function decode(array $encodedEnvelope): Envelope
    {
        if (empty($encodedEnvelope['body']) || empty($encodedEnvelope['headers'])) {
            throw new \InvalidArgumentException('Encoded envelope should have at least a `body` and some `headers`.');
        }

        if (empty($encodedEnvelope['headers']['type'])) {
            throw new \InvalidArgumentException('Encoded envelope does not have a `type` header.');
        }

        $stamps = $this->decodeStamps($encodedEnvelope);

        $message = $this
            ->serializer
            ->deserialize($encodedEnvelope['body'], $encodedEnvelope['headers']['type']);

        return new Envelope($message, ...$stamps);
    }

    /**
     * {@inheritdoc}
     */
    public function encode(Envelope $envelope): array
    {
        $headers = ['type' => \get_class($envelope->getMessage())] + $this->encodeStamps($envelope);

        return [
            'body' => $this->serializer->serialize($envelope->getMessage()),
            'headers' => $headers,
        ];
    }

    /**
     * @param array $encodedEnvelope
     *
     * @return array
     */
    private function decodeStamps(array $encodedEnvelope): array
    {
        $stamps = [];
        foreach ($encodedEnvelope['headers'] as $name => $value) {
            if (0 !== strpos($name, self::STAMP_HEADER_PREFIX)) {
                continue;
            }

            $stamps[] = $this
                ->serializer
                ->deserialize($value, substr($name, \strlen(self::STAMP_HEADER_PREFIX)).'[]');
        }
        if ($stamps) {
            $stamps = array_merge(...$stamps);
        }

        return $stamps;
    }

    /**
     * @return array
     */
    private function encodeStamps(Envelope $envelope): array
    {
        if (!$allStamps = $envelope->all()) {
            return [];
        }

        $headers = [];
        foreach ($allStamps as $class => $stamps) {
            $headers[self::STAMP_HEADER_PREFIX.$class] = $this->serializer->serialize($stamps);
        }

        return $headers;
    }
}
