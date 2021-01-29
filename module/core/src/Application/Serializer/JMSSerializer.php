<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Core\Application\Serializer;

use JMS\Serializer\SerializerInterface as JMSSerializerAlias;
use Psr\Log\LoggerInterface;
use Ergonode\Core\Infrastructure\Exception\SerializationException;
use Ergonode\Core\Infrastructure\Exception\DeserializationException;

class JMSSerializer implements SerializerInterface
{
    private const SERIALIZE = 'Can\'t serialize object "%s" to "%s" format';
    private const DESERIALIZE = 'Can\'t deserialize data "%s" as "%s" from "%s" format';

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
}
