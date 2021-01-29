<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Infrastructure\Serializer;

use JMS\Serializer\SerializerInterface as JMSSerializerAlias;
use Psr\Log\LoggerInterface;
use Ergonode\Core\Infrastructure\Exception\SerializationException;
use Ergonode\Core\Infrastructure\Exception\DeserializationException;

class JMSSerializer implements SerializerInterface
{
    private const DESERIALIZE = 'Can\'t deserialize data "%s" as "%s"';
    private const SERIALIZE = 'Can\'t serialize data';
    private const SERIALIZE_OBJECT = 'Can\'t serialize object "%s"';
    private const FORMAT = 'json';

    private JMSSerializerAlias $serializer;

    private LoggerInterface $logger;

    public function __construct(JMSSerializerAlias $serializer, LoggerInterface $logger)
    {
        $this->serializer = $serializer;
        $this->logger = $logger;
    }

    /**
     * @param mixed $data
     */
    public function serialize($data): string
    {
        try {
            return $this->serializer->serialize($data, 'json');
        } catch (\Throwable $exception) {
            $this->logger->critical($exception);

            if (is_object($data)) {
                throw new SerializationException(sprintf(self::SERIALIZE_OBJECT, get_class($data)), $exception);
            }

            throw new SerializationException(self::SERIALIZE, $exception);
        }
    }

    /**
     * @return mixed
     */
    public function deserialize(string $data, string $type)
    {
        try {
            return $this->serializer->deserialize($data, $type, self::FORMAT);
        } catch (\Throwable $exception) {
            $this->logger->critical($exception);

            throw new DeserializationException(sprintf(self::DESERIALIZE, $data, $type), $exception);
        }
    }
}
