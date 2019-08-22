<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Api\Application\EventListener;

use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

/**
 */
class RequestBodyListener
{
    private const METHODS = [
        Request::METHOD_PUT,
        Request::METHOD_POST,
        Request::METHOD_PATCH,
        Request::METHOD_DELETE,
    ];

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @param SerializerInterface $serializer
     */
    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * @param GetResponseEvent $event
     */
    public function __invoke(GetResponseEvent $event): void
    {
        $request = $event->getRequest();
        if (!in_array($request->getMethod(), self::METHODS, true)) {
            return;
        }

        // @todo check media type

        $content = $request->getContent();
        if (empty($content)) {
            return;
        }

        $data = $this->serializer->deserialize($content, 'array', 'json');
        $request->request = new ParameterBag($data);
    }
}
