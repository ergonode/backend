<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Api\Infrastructure\Normalizer;

/**
 */
class ExceptionDebugNormalizer implements ExceptionNormalizerInterface
{
    /**
     * @var bool
     */
    private $debugMode;

    /**
     * @var ExceptionNormalizerInterface
     */
    private $exceptionNormalizer;

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
            $result['message'] = $exception->getMessage();
            $result['trace'] = explode(PHP_EOL, $exception->getTraceAsString());
        }

        return $result;
    }
}
