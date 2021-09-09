<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Api\Application\EventListener;

use Ergonode\SharedKernel\Application\Serializer\SerializerInterface;
use Ergonode\SharedKernel\Domain\AbstractId;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ViewEvent;

final class ResponseSubscriber implements EventSubscriberInterface
{
    private SerializerInterface $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    public function onViewEvent(ViewEvent $event): void
    {
        $result = $event->getControllerResult();
        if ($result instanceof AbstractId) {
            $result = ['id' => $result->getValue()];
        }

        $body = $this->serializer->serialize($result);

        $response = new Response(
            $body,
            $this->resolveCode($event),
        );

        $event->setResponse($response);
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents(): array
    {
        return [
            ViewEvent::class => 'onViewEvent',
        ];
    }

    private function resolveCode(ViewEvent $event): int
    {
        if (null === $event->getControllerResult()) {
            return Response::HTTP_NO_CONTENT;
        }

        if ($event->getControllerResult() instanceof AbstractId
            && $event->isMasterRequest()
            && Request::METHOD_POST === $event->getRequest()->getMethod()
        ) {
            return Response::HTTP_CREATED;
        }

        return Response::HTTP_OK;
    }
}
