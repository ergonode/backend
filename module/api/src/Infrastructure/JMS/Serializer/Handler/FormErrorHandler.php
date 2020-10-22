<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Api\Infrastructure\JMS\Serializer\Handler;

use Ergonode\Core\Infrastructure\Mapper\FormErrorMapper;
use JMS\Serializer\Context;
use JMS\Serializer\GraphNavigatorInterface;
use JMS\Serializer\Handler\SubscribingHandlerInterface;
use JMS\Serializer\Visitor\SerializationVisitorInterface;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Translation\TranslatorInterface;

class FormErrorHandler implements SubscribingHandlerInterface
{
    private TranslatorInterface $translator;

    private FormErrorMapper $formErrorMapper;

    public function __construct(TranslatorInterface $translator, FormErrorMapper $formErrorMapper)
    {
        $this->translator = $translator;
        $this->formErrorMapper = $formErrorMapper;
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
                'type' => Form::class,
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
    public function serialize(SerializationVisitorInterface $visitor, Form $form, array $type, Context $context): array
    {
        return $visitor->visitArray(
            [
                'code' => Response::HTTP_BAD_REQUEST,
                'message' => $this->translator->trans('Form validation error'),
                'errors' => $this->formErrorMapper->map($form),
            ],
            $type
        );
    }
}
