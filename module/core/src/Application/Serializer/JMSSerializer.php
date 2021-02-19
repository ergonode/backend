<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Application\Serializer;

use Ergonode\Core\Application\Exception\DenoralizationException;
use Ergonode\Core\Application\Exception\DeserializationException;
use Ergonode\Core\Application\Exception\NormalizerException;
use Ergonode\Core\Application\Exception\SerializationException;
use JMS\Serializer\Serializer;
use Psr\Log\LoggerInterface;

class JMSSerializer implements SerializerInterface, NormalizerInterface
{
    private const SERIALIZE = 'Can\'t serialize data "%s" to "%s" format';
    private const DESERIALIZE = 'Can\'t deserialize data "%s" as "%s" from "%s" format';
    private const NORMALIZE = 'Can\'t normalize data "%s"';
    private const DENORMALIZE = 'Can\'t denormalize data from "%s" to "%s" type';

    private Serializer $serializer;

    private LoggerInterface $logger;

    public function __construct(Serializer $serializer, LoggerInterface $logger)
    {
        $this->serializer = $serializer;
        $this->logger = $logger;
    }

    /**
     * @param mixed $data
     */
    public function serialize($data, ?string $format = self::FORMAT): string
    {
        try {
            return $this->serializer->serialize($data, $format);
        } catch (\Throwable $exception) {
            $this->logger->error($exception);

            throw new SerializationException(
                sprintf(self::SERIALIZE, get_debug_type($data), $format),
                $exception
            );
        }
    }

    /**
     * @return mixed
     */
    public function deserialize(string $data, string $type, ?string $format = self::FORMAT)
    {
        try {
            return $this->serializer->deserialize($data, $type, $format);
        } catch (\Throwable $exception) {
            $this->logger->error($exception);

            throw new DeserializationException(
                sprintf(self::DESERIALIZE, $data, $type, $format),
                $exception
            );
        }
    }

    /**
     * {@inheritdoc}
     */
    public function normalize($data): array
    {
        try {
            return $this->serializer->toArray($data);
        } catch (\Throwable $exception) {
            $this->logger->error($exception);

            throw new NormalizerException(
                sprintf(self::NORMALIZE, get_debug_type($data)),
                $exception
            );
        }
    }

    /**
     * {@inheritdoc}
     */
    public function denormalize($data, string $type)
    {
        if (!is_array($data)) {
            throw new \InvalidArgumentException('Only array type supported for data');
        }

        try {
            return $this->serializer->fromArray($data, $type);
        } catch (\Throwable $exception) {
            $this->logger->error($exception);

            throw new DenoralizationException(
                sprintf(self::DENORMALIZE, get_debug_type($data), $type),
                $exception
            );
        }
    }
}
