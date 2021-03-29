<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Api\Application\Serializer\Normalizer;

use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Ergonode\Api\Application\Mapper\ExceptionMapperInterface;
use Ergonode\Api\Infrastructure\Normalizer\ExceptionNormalizerInterface;
use Ergonode\Api\Application\Exception\FormValidationHttpException;
use Ergonode\Api\Application\Exception\ViolationsHttpException;

class ExceptionNormalizer implements NormalizerInterface
{
    private const DEFAULT_CODE = Response::HTTP_INTERNAL_SERVER_ERROR;
    private const DEFAULT_MESSAGE = 'Internal server error';

    private ExceptionMapperInterface $exceptionMapper;

    private ExceptionNormalizerInterface $exceptionNormalizer;

    public function __construct(
        ExceptionMapperInterface $exceptionMapper,
        ExceptionNormalizerInterface $exceptionNormalizer
    ) {
        $this->exceptionMapper = $exceptionMapper;
        $this->exceptionNormalizer = $exceptionNormalizer;
    }

    /**
     * {@inheritdoc}
     */
    public function normalize($exception, $format = null, array $context = [])
    {
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

        return $this->exceptionNormalizer->normalize($exception, (string) $code, $message);
    }

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization($data, $format = null)
    {
        return $data instanceof \Exception
            && !$data instanceof FormValidationHttpException
            && !$data instanceof ViolationsHttpException;
    }
}
