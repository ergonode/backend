<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
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

/**
 */
class ViolationsExceptionHandler implements SubscribingHandlerInterface
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
     * @param SerializationVisitorInterface $visitor
     * @param ViolationsHttpException       $exception
     * @param array                         $type
     * @param Context                       $context
     *
     * @return array
     */
    public function serialize(
        SerializationVisitorInterface $visitor,
        ViolationsHttpException $exception,
        array $type,
        Context $context
    ): array {
        $data = $this->exceptionNormalizer->normalize($exception, (string) Response::HTTP_BAD_REQUEST);
        $data['errors'] = $this->mapViolations($exception->getViolations());

        return $visitor->visitArray($data, $type);
    }

    /**
     * @param ConstraintViolationListInterface $violations
     *
     * @return array
     */
    private function mapViolations(ConstraintViolationListInterface $violations): array
    {
        $errors = [];
        /** @var ConstraintViolationInterface $violation */
        foreach ($violations as $violation) {
            $field = substr($violation->getPropertyPath(), 1, -1);

            if (!array_key_exists($field, $errors)) {
                $errors[$field] = [];
            }

            $errors[$field][] = $violation->getMessage();
        }

        return $errors;
    }
}
