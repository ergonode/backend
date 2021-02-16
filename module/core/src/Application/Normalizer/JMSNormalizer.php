<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Application\Normalizer;

use Ergonode\Core\Infrastructure\Exception\DenoralizationException;
use Ergonode\Core\Infrastructure\Exception\NormalizerException;
use JMS\Serializer\ArrayTransformerInterface;
use JMS\Serializer\DeserializationContext;
use JMS\Serializer\SerializationContext;
use Psr\Log\LoggerInterface;

class JMSNormalizer implements NormalizerInterface
{
    private const NORMALIZE = 'Can\'t normalize object "%s"';
    private const DENORMALIZE = 'Can\'t denormalize data from "%s" to "%s" type';

    private ArrayTransformerInterface $transformer;

    private LoggerInterface $logger;

    public function __construct(ArrayTransformerInterface $transformer, LoggerInterface $logger)
    {
        $this->transformer = $transformer;
        $this->logger = $logger;
    }


    public function normalize(object $data, ?SerializationContext $context, ?string $type): array
    {
        try {
            return $this->transformer->toArray($data, $context, $type);
        } catch (\Throwable $exception) {
            $this->logger->error($exception);

            throw new NormalizerException(
                sprintf(self::NORMALIZE, get_debug_type($data)),
                $exception
            );
        }
    }

    public function denormalize(array $data, string $type, ?DeserializationContext $context): object
    {
        try {
            return $this->transformer->fromArray($data, $type, $context);
        } catch (\Throwable $exception) {
            $this->logger->error($exception);

            throw new DenoralizationException(
                sprintf(self::DENORMALIZE, get_debug_type($data), $type),
                $exception
            );
        }
    }
}
