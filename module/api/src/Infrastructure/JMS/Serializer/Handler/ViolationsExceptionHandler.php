<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Api\Infrastructure\JMS\Serializer\Handler;

use Ergonode\Api\Application\Exception\ViolationsHttpException;
use Ergonode\Api\Infrastructure\Normalizer\ExceptionNormalizerInterface;
use JMS\Serializer\Context;
use JMS\Serializer\GraphNavigatorInterface;
use JMS\Serializer\Handler\SubscribingHandlerInterface;
use JMS\Serializer\Visitor\SerializationVisitorInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class ViolationsExceptionHandler implements SubscribingHandlerInterface
{
    private ExceptionNormalizerInterface $exceptionNormalizer;

    public function __construct(ExceptionNormalizerInterface $exceptionNormalizer)
    {
        $this->exceptionNormalizer = $exceptionNormalizer;
    }

    /**
     * @return array
     */
    public static function getSubscribingMethods(): array
    {
        $methods = [];
        $formats = ['json'];

        foreach ($formats as $format) {
            $methods[] = [
                'direction' => GraphNavigatorInterface::DIRECTION_SERIALIZATION,
                'type' => ViolationsHttpException::class,
                'format' => $format,
                'method' => 'serialize',
            ];
        }

        return $methods;
    }

    /**
     * @param array $type
     *
     * @return array
     */
    public function serialize(
        SerializationVisitorInterface $visitor,
        ViolationsHttpException $exception,
        array $type,
        Context $context
    ): array {
        $data = $this
            ->exceptionNormalizer
            ->normalize($exception, (string) Response::HTTP_BAD_REQUEST, $exception->getMessage());
        $data['errors'] = $this->mapViolations($exception->getViolations());

        return $visitor->visitArray($data, $type);
    }

    /**
     * @return array
     */
    private function mapViolations(ConstraintViolationListInterface $violations): array
    {
        $errors = [];
        /** @var ConstraintViolationInterface $violation */
        foreach ($violations as $violation) {
            $field = ltrim(str_replace(['[', ']'], ['.', ''], $violation->getPropertyPath()), '.');
            $path = explode('.', $field);

            $pointer = &$errors;
            foreach ($path as $key) {
                if (ctype_digit($key)) {
                    $key = 'element-'.$key;
                }

                if (!array_key_exists($key, $pointer)) {
                    $pointer[$key] = [];
                }

                $pointer = &$pointer[$key];
            }

            $pointer[] = $violation->getMessage();
        }

        return $errors;
    }
}
