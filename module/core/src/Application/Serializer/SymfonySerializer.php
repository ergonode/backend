<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Application\Serializer;

use Ergonode\SharedKernel\Application\Serializer\Exception\DenoralizationException;
use Ergonode\SharedKernel\Application\Serializer\Exception\DeserializationException;
use Ergonode\SharedKernel\Application\Serializer\Exception\NormalizationException;
use Ergonode\SharedKernel\Application\Serializer\Exception\SerializationException;
use Ergonode\SharedKernel\Application\Serializer\NormalizerInterface;
use Ergonode\SharedKernel\Application\Serializer\SerializerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Serializer;

class SymfonySerializer implements SerializerInterface, NormalizerInterface
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
     * {@inheritdoc}
     */
    public function serialize($data, ?string $format = self::FORMAT): string
    {
        try {
            return $this->serializer->serialize($data, $format);
        } catch (ExceptionInterface $exception) {
            $this->logger->error($exception);

            throw new SerializationException(
                sprintf(self::SERIALIZE, get_debug_type($data), $format),
                $exception
            );
        }
    }

    /**
     * {@inheritdoc}
     */
    public function deserialize(string $data, string $type, ?string $format = self::FORMAT)
    {
        try {
            return $this->serializer->deserialize($data, $type, $format);
        } catch (ExceptionInterface $exception) {
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
    public function normalize($data)
    {
        try {
            return $this->serializer->normalize($data);
        } catch (ExceptionInterface $exception) {
            $this->logger->error($exception);

            throw new NormalizationException(
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
        try {
            return $this->serializer->denormalize($data, $type);
        } catch (ExceptionInterface $exception) {
            $this->logger->error($exception);

            throw new DenoralizationException(
                sprintf(self::DENORMALIZE, get_debug_type($data), $type),
                $exception
            );
        }
    }
}
