<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\BatchAction\Application\Serializer\Normalizer;

use Ergonode\BatchAction\Domain\Command\AbstractPayloadCommand;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Symfony\Component\Serializer\Exception\NotNormalizableValueException;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class PayloadCommandNormalizer implements
    NormalizerInterface,
    DenormalizerInterface,
    CacheableSupportsMethodInterface
{
    private AbstractObjectNormalizer $objectNormalizer;

    public function __construct(AbstractObjectNormalizer $objectNormalizer)
    {
        $this->objectNormalizer = $objectNormalizer;
    }

    /**
     * {@inheritdoc}
     */
    public function denormalize($data, $type, $format = null, array $context = []): AbstractPayloadCommand
    {
        if (!is_subclass_of($type, AbstractPayloadCommand::class)) {
            throw new NotNormalizableValueException('Only AbstractPayloadCommand type supported.');
        }
        if (!is_array($data)) {
            throw new NotNormalizableValueException('Data is expected to be an array.');
        }
        if (!array_key_exists('payload', $data)) {
            throw new NotNormalizableValueException('Payload key has to be passed.');
        }
        $payloadVal = unserialize($data['payload']);
        unset($data['payload']);

        $command = $this->objectNormalizer->denormalize($data, $type, $format, $context);

        $payload = new \ReflectionProperty($command, 'payload');
        $payload->setAccessible(true);
        $payload->setValue($command, $payloadVal);

        return $command;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsDenormalization($data, $type, $format = null): bool
    {
        return is_subclass_of($type, AbstractPayloadCommand::class);
    }

    /**
     * {@inheritdoc}
     */
    public function normalize($object, $format = null, array $context = []): array
    {
        if (!$object instanceof AbstractPayloadCommand) {
            throw new InvalidArgumentException(sprintf(
                'The object must be an instance of "%s"',
                AbstractPayloadCommand::class,
            ));
        }
        $clone = clone $object;
        $payload = new \ReflectionProperty($clone, 'payload');
        $payload->setAccessible(true);
        $payload->setValue($clone, null);

        $command = $this->objectNormalizer->normalize($clone, $format, $context);

        $command['payload'] = serialize($object->getPayload());

        return $command;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization($data, $format = null): bool
    {
        return $data instanceof AbstractPayloadCommand;
    }

    /**
     * {@inheritdoc}
     */
    public function hasCacheableSupportsMethod(): bool
    {
        return __CLASS__ === static::class;
    }
}
