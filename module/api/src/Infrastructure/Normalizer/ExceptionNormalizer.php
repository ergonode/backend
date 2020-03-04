<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Api\Infrastructure\Normalizer;

use Symfony\Contracts\Translation\TranslatorInterface;

/**
 */
class ExceptionNormalizer implements ExceptionNormalizerInterface
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
     * {@inheritDoc}
     */
    public function normalize(\Exception $exception, ?string $code = null, ?string $message = null): array
    {
        $result = [
            'code' => $code ?? $exception->getCode(),
            'message' => null !== $message ? $this->translator->trans($message, [], 'api') : null,
        ];

        return $result;
    }
}
