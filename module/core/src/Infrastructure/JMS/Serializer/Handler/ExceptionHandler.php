<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Core\Infrastructure\JMS\Serializer\Handler;

use FOS\RestBundle\Util\ExceptionValueMap;
use JMS\Serializer\Context;

/**
 */
class ExceptionHandler extends \FOS\RestBundle\Serializer\Normalizer\ExceptionHandler
{
    /**
     * @var bool
     */
    private $debug;

    /**
     * @param ExceptionValueMap $messagesMap
     * @param bool              $debug
     */
    public function __construct(ExceptionValueMap $messagesMap, $debug)
    {
        parent::__construct($messagesMap, $debug);
        $this->debug = $debug;
    }

    /**
     * {@inheritDoc}
     */
    protected function convertToArray(\Exception $exception, Context $context): array
    {
        $data = [];
        if ($context->hasAttribute('template_data')) {
            $templateData = $context->getAttribute('template_data');
            if (array_key_exists('status_code', $templateData)) {
                $data['code'] = $statusCode = $templateData['status_code'];
            }
        }

        $data['message'] = $this->getExceptionMessage($exception, $statusCode ?? null);

        if ($this->debug) {
            $data['trace'] = explode(PHP_EOL, $exception->getTraceAsString());
            $data['memory'] = number_format(memory_get_peak_usage(true)/1024, 2, '.', '');
        }

        return $data;
    }
}
