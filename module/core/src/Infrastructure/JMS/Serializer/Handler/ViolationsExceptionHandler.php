<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Core\Infrastructure\JMS\Serializer\Handler;

use Ergonode\Core\Application\Exception\ViolationsHttpException;
use JMS\Serializer\Context;
use JMS\Serializer\GraphNavigatorInterface;
use JMS\Serializer\Handler\SubscribingHandlerInterface;
use JMS\Serializer\Visitor\SerializationVisitorInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 */
class ViolationsExceptionHandler implements SubscribingHandlerInterface
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @param TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * @return array
     */
    public static function getSubscribingMethods(): array
    {
        $methods = [];
        $formats = ['json', 'xml', 'yml'];

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
     * @return mixed
     */
    public function serialize(SerializationVisitorInterface $visitor, ViolationsHttpException $exception, array $type, Context $context)
    {
        $errors = [];
        /** @var ConstraintViolationInterface $violation */
        foreach ($exception->getViolations() as $violation) {
            $field = substr($violation->getPropertyPath(), 1, -1);
            $errors[$field] = [$violation->getMessage()];
        }

        return [
            'code' => Response::HTTP_BAD_REQUEST,
            'message' => $this->translator->trans('Validation error'),
            'errors' => $errors,
        ];
    }
}
