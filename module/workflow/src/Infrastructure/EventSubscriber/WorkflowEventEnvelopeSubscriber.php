<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Infrastructure\EventSubscriber;

use Ergonode\Core\Application\Provider\AuthenticatedUserProviderInterface;
use Ergonode\EventSourcing\Infrastructure\Envelope\DomainEventEnvelope;
use Ergonode\Notification\Domain\Command\SendNotificationCommand;
use Ergonode\Product\Domain\Entity\ProductId;
use Ergonode\Product\Domain\Event\ProductValueChanged;
use Ergonode\Product\Domain\Repository\ProductRepositoryInterface;
use Ergonode\Workflow\Domain\Entity\Attribute\StatusAttribute;
use Ergonode\Workflow\Domain\Entity\Workflow;
use Ergonode\Workflow\Domain\Entity\WorkflowId;
use Ergonode\Workflow\Domain\Notification\StatusChangedNotification;
use Ergonode\Workflow\Domain\Repository\WorkflowRepositoryInterface;
use Ergonode\Workflow\Domain\ValueObject\StatusCode;
use Ergonode\Workflow\Infrastructure\Provider\UserIdsProvider;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Webmozart\Assert\Assert;

/**
 */
class WorkflowEventEnvelopeSubscriber implements EventSubscriberInterface
{
    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @var WorkflowRepositoryInterface
     */
    private $workflowRepository;

    /**
     * @var UserIdsProvider
     */
    private $userIdsProvider;

    /**
     * @var AuthenticatedUserProviderInterface
     */
    private $userProvider;

    /**
     * @var MessageBusInterface
     */
    private $bus;

    /**
     * @param ProductRepositoryInterface         $productRepository
     * @param WorkflowRepositoryInterface        $workflowRepository
     * @param UserIdsProvider                    $userIdsProvider
     * @param AuthenticatedUserProviderInterface $userProvider
     * @param MessageBusInterface                $bus
     */
    public function __construct(
        ProductRepositoryInterface $productRepository,
        WorkflowRepositoryInterface $workflowRepository,
        UserIdsProvider $userIdsProvider,
        AuthenticatedUserProviderInterface $userProvider,
        MessageBusInterface $bus
    ) {
        $this->productRepository = $productRepository;
        $this->workflowRepository = $workflowRepository;
        $this->userIdsProvider = $userIdsProvider;
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
        if ($event instanceof ProductValueChanged) {
            $attributeCode = $event->getAttributeCode();
            if ($attributeCode->getValue() === StatusAttribute::CODE) {
                $workflowId = WorkflowId::fromCode(Workflow::DEFAULT);
                $workflow = $this->workflowRepository->load($workflowId);
                Assert::notNull($workflow);
                $source = new StatusCode($event->getFrom()->getValue());
                $destination = new StatusCode($event->getTo()->getValue());
                if ($workflow->hasTransition($source, $destination)) {
                    $transition = $workflow->getTransition($source, $destination);
                    if (!empty($transition->getRoleIds())) {
                        $productId = new ProductId($envelope->getAggregateId()->getValue());
                        $product = $this->productRepository->load($productId);
                        Assert::notNull($product);

                        $roleIds = $transition->getRoleIds();
                        $recipients = $this->userIdsProvider->getUserIds($roleIds);
                        $user = $this->userProvider->provide();

                        $notification = new StatusChangedNotification($product->getSku(), $transition->getFrom(), $transition->getTo(), $user);
                        $command = new SendNotificationCommand($notification, $recipients);

                        $this->bus->dispatch($command);
                    }
                }
            }
        }
    }
}
