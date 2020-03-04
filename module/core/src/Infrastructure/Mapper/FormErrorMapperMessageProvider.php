<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Core\Infrastructure\Mapper;

use Symfony\Component\Form\FormError;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 */
class FormErrorMapperMessageProvider
{
    /**
     * @var TranslatorInterface
     */
    private TranslatorInterface $translator;

    /**
     * @param TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * @param FormError $error
     *
     * @return string
     */
    public function getMessage(FormError $error): string
    {
        $parameters = $error->getMessageParameters();
        if (null !== $error->getMessagePluralization()) {
            $parameters = array_merge($parameters, ['%count%' => $error->getMessagePluralization()]);
        }

        return $this->translator->trans($error->getMessageTemplate(), $parameters);
    }
}
