<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Core\Infrastructure\JMS\Serializer\Handler;

use JMS\Serializer\Context;
use JMS\Serializer\GraphNavigatorInterface;
use JMS\Serializer\Visitor\SerializationVisitorInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 */
class AccessDeniedExceptionHandler
{
    /**
     * @var bool
     */
    private $debug;

    /**
     * @param bool $debug
     */
    public function __construct(bool $debug)
    {
        $this->debug = $debug;
    }

    /**
     * {@inheritDoc}
     */
    public static function getSubscribingMethods(): array
    {
        $methods = [];
        $formats = ['json', 'xml', 'yml'];

        foreach ($formats as $format) {
            $methods[] = [
                'direction' => GraphNavigatorInterface::DIRECTION_SERIALIZATION,
                'type' => AccessDeniedException::class,
                'format' => $format,
                'method' => 'serialize',
            ];
        }

        return $methods;
    }

    /**
     * @param SerializationVisitorInterface $visitor
     * @param \Exception                    $exception
     * @param array                         $type
     * @param Context                       $context
     *
     * @return array
     *
     * @todo Create ExceptionFormatter or something like that
     */
    public function serialize(SerializationVisitorInterface $visitor, \Exception $exception, array $type, Context $context): array
    {
        $data = [
            'code' => Response::HTTP_FORBIDDEN,
            'message' => 'Access denied',
        ];

        if ($this->debug) {
            $data['message'] = $exception->getMessage();
            $data['trace'] = explode(PHP_EOL, $exception->getTraceAsString());
        }

        return $visitor->visitArray($data, $type);
    }
}
