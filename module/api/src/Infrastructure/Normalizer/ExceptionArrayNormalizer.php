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
class ExceptionArrayNormalizer implements ExceptionNormalizerInterface
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var bool
     */
    private $debugMode;

    /**
     * @param TranslatorInterface $translator
     * @param bool                $debugMode
     */
    public function __construct(TranslatorInterface $translator, bool $debugMode)
    {
        $this->translator = $translator;
        $this->debugMode = $debugMode;
    }

    /**
     * {@inheritDoc}
     */
    public function normalize(\Exception $exception, ?string $code = null, ?string $message = null): array
    {
        $result = [
            'code' => $code ?? $exception->getCode(),
            'message' => $message ?? $this->translator->trans($message, [], 'api'),
        ];

        if ($this->debugMode) {
            $result['message'] = $exception->getMessage();
            $result['trace'] = explode(PHP_EOL, $exception->getTraceAsString());
        }

        return $result;
    }
}
