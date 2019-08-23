<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Api\Infrastructure\JMS\Serializer\Handler;

use Ergonode\Api\Application\Mapper\ExceptionResponseMapper;
use Ergonode\Api\Infrastructure\Normalizer\ExceptionNormalizerInterface;
use JMS\Serializer\Context;
use JMS\Serializer\GraphNavigatorInterface;
use JMS\Serializer\Handler\SubscribingHandlerInterface;
use JMS\Serializer\Visitor\SerializationVisitorInterface;

/**
 */
class ExceptionHandler implements SubscribingHandlerInterface
{
    /**
     * @var ExceptionResponseMapper
     */
    private $exceptionResponseMapper;

    /**
     * @var ExceptionNormalizerInterface
     */
    private $exceptionNormalizer;

    /**
     * @param ExceptionResponseMapper      $exceptionResponseMapper
     * @param ExceptionNormalizerInterface $exceptionNormalizer
     */
    public function __construct(
        ExceptionResponseMapper $exceptionResponseMapper,
        ExceptionNormalizerInterface $exceptionNormalizer
    ) {
        $this->exceptionResponseMapper = $exceptionResponseMapper;
        $this->exceptionNormalizer = $exceptionNormalizer;
    }

    /**
     * {@inheritDoc}
     */
    public static function getSubscribingMethods(): array
    {
        $methods = [];
        $formats = ['json'];

        foreach ($formats as $format) {
            $methods[] = [
                'direction' => GraphNavigatorInterface::DIRECTION_SERIALIZATION,
                'type' => \Exception::class,
                'format' => $format,
                'method' => 'serialize',
            ];
        }

        return $methods;
    }

    /**
     * @param SerializationVisitorInterface $visitor
     * @param \Exception                    $exception
     * @param array                         $type
     * @param Context                       $context
     *
     * @return array
     */
    public function serialize(
        SerializationVisitorInterface $visitor,
        \Exception $exception,
        array $type,
        Context $context
    ): array {
        $code = $exception->getCode();
        if (empty($code)) {
            $code = 'UNKNOWN';
        }

        $message = 'Internal server error';

        $data = $this->exceptionResponseMapper->map($exception);
        if (null !== $data) {
            if (null !== $data['content']['code']) {
                $code = $data['content']['code'];
            }

            if (null !== $data['content']['message']) {
                $message = $data['content']['message'];
            }
        }

        $data = $this->exceptionNormalizer->normalize($exception, (string) $code, $message);

        return $visitor->visitArray($data, $type);
    }
}
