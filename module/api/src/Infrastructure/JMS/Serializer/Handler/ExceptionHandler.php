<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Api\Infrastructure\JMS\Serializer\Handler;

use Ergonode\Api\Application\Mapper\ExceptionMapperInterface;
use Ergonode\Api\Infrastructure\Normalizer\ExceptionNormalizerInterface;
use JMS\Serializer\Context;
use JMS\Serializer\GraphNavigatorInterface;
use JMS\Serializer\Handler\SubscribingHandlerInterface;
use JMS\Serializer\Visitor\SerializationVisitorInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ExceptionHandler implements SubscribingHandlerInterface
{
    private const DEFAULT_CODE = Response::HTTP_INTERNAL_SERVER_ERROR;
    private const DEFAULT_MESSAGE = 'Internal server error';

    /**
     * @var ExceptionMapperInterface
     */
    private ExceptionMapperInterface $exceptionMapper;

    /**
     * @var ExceptionNormalizerInterface
     */
    private ExceptionNormalizerInterface $exceptionNormalizer;

    /**
     * @param ExceptionMapperInterface     $exceptionMapper
     * @param ExceptionNormalizerInterface $exceptionNormalizer
     */
    public function __construct(
        ExceptionMapperInterface $exceptionMapper,
        ExceptionNormalizerInterface $exceptionNormalizer
    ) {
        $this->exceptionMapper = $exceptionMapper;
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
        if (!$exception instanceof HttpException) {
            $code = $exception->getCode();
            $message = self::DEFAULT_MESSAGE;
        } else {
            $code = $exception->getStatusCode();
            $message = $exception->getMessage();
        }

        if (empty($code)) {
            $code = self::DEFAULT_CODE;
        }

        $configuration = $this->exceptionMapper->map($exception);
        if (null !== $configuration) {
            $code = $configuration['content']['code'] ?? $code;
            $message = $configuration['content']['message'] ?? $message;
        }

        $data = $this->exceptionNormalizer->normalize($exception, (string) $code, $message);

        return $visitor->visitArray($data, $type);
    }
}
