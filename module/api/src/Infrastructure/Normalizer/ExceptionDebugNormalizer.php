<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Api\Infrastructure\Normalizer;

class ExceptionDebugNormalizer implements ExceptionNormalizerInterface
{
    /**
     * @var bool
     */
    private bool $debugMode;

    /**
     * @var ExceptionNormalizerInterface
     */
    private ExceptionNormalizerInterface $exceptionNormalizer;

    /**
     * @param bool                         $debugMode
     * @param ExceptionNormalizerInterface $exceptionNormalizer
     */
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
     * @param \Exception $exception
     *
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
