<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Api\Application\Serializer\Normalizer;

use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\HttpFoundation\Response;
use Ergonode\Api\Infrastructure\Normalizer\ExceptionNormalizerInterface;
use Ergonode\Api\Application\Exception\ViolationsHttpException;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\ConstraintViolationInterface;

class ViolationExceptionNormalizer implements NormalizerInterface
{
    private ExceptionNormalizerInterface $exceptionNormalizer;

    public function __construct(ExceptionNormalizerInterface $exceptionNormalizer)
    {
        $this->exceptionNormalizer = $exceptionNormalizer;
    }

    /**
     * {@inheritdoc}
     */
    public function normalize($exception, $format = null, array $context = [])
    {
        $data = $this
            ->exceptionNormalizer
            ->normalize($exception, (string) Response::HTTP_BAD_REQUEST, $exception->getMessage());
        $data['errors'] = $this->mapViolations($exception->getViolations());

        return $data;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization($data, $format = null)
    {
        return $data instanceof ViolationsHttpException;
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
