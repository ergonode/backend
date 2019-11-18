<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Infrastructure\EventSubscriber;

use Ergonode\Authentication\Application\Security\Provider\DomainUserProvider;
use Ergonode\Core\Application\Provider\AuthenticatedUserProviderInterface;
use Ergonode\Editor\Domain\Event\ProductDraftValueChanged;
use Ergonode\EventSourcing\Infrastructure\Envelope\DomainEventEnvelope;
use Ergonode\Notification\Domain\Command\SendNotificationCommand;
use Ergonode\Workflow\Domain\Entity\Attribute\StatusAttribute;
use Ergonode\Workflow\Domain\Entity\Workflow;
use Ergonode\Workflow\Domain\Entity\WorkflowId;
use Ergonode\Workflow\Domain\Repository\WorkflowRepositoryInterface;
use Ergonode\Workflow\Domain\ValueObject\StatusCode;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Webmozart\Assert\Assert;

/**
 */
class WorkflowEventEnvelopeSubscriber implements EventSubscriberInterface
{
    /**
     * @var WorkflowRepositoryInterface
     */
    private $workflowRepository;

    /**
     * @var AuthenticatedUserProviderInterface
     */
    private $userProvider;

    /**
     * @var MessageBusInterface
     */
    private $bus;

    /**
     * @param WorkflowRepositoryInterface        $workflowRepository
     * @param AuthenticatedUserProviderInterface $userProvider
     * @param MessageBusInterface                $bus
     */
    public function __construct(WorkflowRepositoryInterface $workflowRepository, AuthenticatedUserProviderInterface $userProvider, MessageBusInterface $bus)
    {
        $this->workflowRepository = $workflowRepository;
        $this->userProvider = $userProvider;
        $this->bus = $bus;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            DomainEventEnvelope::class => 'handle',
        ];
    }

    /**
     * @param DomainEventEnvelope $envelope
     *
     * @throws \Exception
     */
    public function handle(DomainEventEnvelope $envelope): void
    {
        $event = $envelope->getEvent();
        if ($event instanceof ProductDraftValueChanged) {
            $attributeCode = $event->getAttributeCode();
            if ($attributeCode->getValue() === StatusAttribute::CODE) {
                $workflowId = WorkflowId::fromCode(Workflow::DEFAULT);
                $workflow = $this->workflowRepository->load($workflowId);
                Assert::notNull($workflow);
                $source = new StatusCode($event->getFrom()->getValue());
                $destination = new StatusCode($event->getTo()->getValue());
                if ($workflow->hasTransition($source, $destination)) {
                    $transition = $workflow->getTransition($source, $destination);

                    $user = $this->userProvider->provide();

                    $command = new SendNotificationCommand('Create notification' , $user->getRoleId(), $user->getId());

                    $this->bus->dispatch($command);
                }
            }
        }
    }
}
