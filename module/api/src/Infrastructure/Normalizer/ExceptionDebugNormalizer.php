<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Api\Infrastructure\Normalizer;

class ExceptionDebugNormalizer implements ExceptionNormalizerInterface
{
    private bool $debugMode;

    private ExceptionNormalizerInterface $exceptionNormalizer;

    public function __construct(
        bool $debugMode,
        ExceptionNormalizerInterface $exceptionNormalizer
    ) {
        $this->debugMode = $debugMode;
        $this->exceptionNormalizer = $exceptionNormalizer;
    }

    /**
     * {@inheritDoc}
     */
    public function normalize(\Exception $exception, ?string $code = null, ?string $message = null): array
    {
        $result = $this->exceptionNormalizer->normalize($exception, $code, $message);

        if ($this->debugMode) {
            $result['exception'] = [
                'current' => $this->formatException($exception),
            ];

            if ($exception->getPrevious() instanceof \Exception) {
                $result['exception']['previous'] = $this->formatException($exception->getPrevious());
            }
        }

        return $result;
    }

    /**
     * @return array
     */
    private function formatException(\Exception $exception): array
    {
        return [
            'message' => $exception->getMessage(),
            'trace' => explode(PHP_EOL, $exception->getTraceAsString()),
        ];
    }
}
