<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Api\Infrastructure\JMS\Serializer\Handler;

use Ergonode\Api\Application\Exception\FormValidationHttpException;
use JMS\Serializer\Context;
use JMS\Serializer\GraphNavigatorInterface;
use JMS\Serializer\Handler\SubscribingHandlerInterface;
use JMS\Serializer\Visitor\SerializationVisitorInterface;

class FormValidationExceptionHandler implements SubscribingHandlerInterface
{
    /**
     * @var FormErrorHandler
     */
    private FormErrorHandler $formErrorHandler;

    /**
     * @param FormErrorHandler $formErrorHandler
     */
    public function __construct(FormErrorHandler $formErrorHandler)
    {
        $this->formErrorHandler = $formErrorHandler;
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
                'type' => FormValidationHttpException::class,
                'format' => $format,
                'method' => 'serialize',
            ];
        }

        return $methods;
    }

    /**
     * @param SerializationVisitorInterface $visitor
     * @param FormValidationHttpException   $exception
     * @param array                         $type
     * @param Context                       $context
     *
     * @return array
     */
    public function serialize(
        SerializationVisitorInterface $visitor,
        FormValidationHttpException $exception,
        array $type,
        Context $context
    ): array {
        return $this->formErrorHandler->serialize($visitor, $exception->getForm(), $type, $context);
    }
}
