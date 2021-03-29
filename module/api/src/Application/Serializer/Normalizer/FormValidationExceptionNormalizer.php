<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Api\Application\Serializer\Normalizer;

use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Ergonode\Api\Application\Exception\FormValidationHttpException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\Translation\TranslatorInterface;
use Ergonode\Core\Infrastructure\Mapper\FormErrorMapper;

class FormValidationExceptionNormalizer implements NormalizerInterface
{
    private TranslatorInterface $translator;

    private FormErrorMapper $formErrorMapper;

    public function __construct(TranslatorInterface $translator, FormErrorMapper $formErrorMapper)
    {
        $this->translator = $translator;
        $this->formErrorMapper = $formErrorMapper;
    }

    /**
     * {@inheritdoc}
     */
    public function normalize($object, $format = null, array $context = []): array
    {
        return [
            'code' => Response::HTTP_BAD_REQUEST,
            'message' => $this->translator->trans('Form validation error'),
            'errors' => $this->formErrorMapper->map($object->getForm()),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization($data, $format = null)
    {
        return $data instanceof FormValidationHttpException;
    }
}
