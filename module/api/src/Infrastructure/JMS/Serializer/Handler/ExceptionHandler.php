<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Api\Infrastructure\JMS\Serializer\Handler;

use Ergonode\Api\Infrastructure\Normalizer\ExceptionNormalizerInterface;
use JMS\Serializer\Context;
use JMS\Serializer\GraphNavigatorInterface;
use JMS\Serializer\Handler\SubscribingHandlerInterface;
use JMS\Serializer\Visitor\SerializationVisitorInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

/**
 */
class ExceptionHandler implements SubscribingHandlerInterface
{
    /**
     * @var ExceptionNormalizerInterface
     */
    private $exceptionNormalizer;

    /**
     * @param ExceptionNormalizerInterface $exceptionNormalizer
     */
    public function __construct(ExceptionNormalizerInterface $exceptionNormalizer)
    {
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
        $code = $exception instanceof HttpExceptionInterface ? $exception->getStatusCode() : $exception->getCode();
        if (empty($code)) {
            $code = Response::HTTP_INTERNAL_SERVER_ERROR;
        }

        $data = $this->exceptionNormalizer->normalize(
            $exception,
            (string) $code,
            'Internal server error'
        );

        return $visitor->visitArray($data, $type);
    }
}
