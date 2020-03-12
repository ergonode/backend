<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Infrastructure\Handler\Event;

use Ergonode\Account\Infrastructure\Provider\AuthenticatedUserProviderInterface;
use Ergonode\EventSourcing\Infrastructure\Bus\CommandBusInterface;
use Ergonode\Notification\Domain\Command\SendNotificationCommand;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use Ergonode\Product\Domain\Event\ProductValueChangedEvent;
use Ergonode\Product\Domain\Repository\ProductRepositoryInterface;
use Ergonode\Workflow\Domain\Entity\Attribute\StatusSystemAttribute;
use Ergonode\Workflow\Domain\Notification\StatusChangedNotification;
use Ergonode\Workflow\Domain\ValueObject\StatusCode;
use Ergonode\Workflow\Infrastructure\Provider\UserIdsProvider;
use Webmozart\Assert\Assert;
use Ergonode\Workflow\Domain\Provider\WorkflowProvider;

/**
 */
class ProductValueChangedEventHandler
{
    /**
     * @var ProductRepositoryInterface
     */
    private ProductRepositoryInterface $productRepository;

    /**
     * @var WorkflowProvider
     */
    private WorkflowProvider $workflowProvider;

    /**
     * @var UserIdsProvider
     */
    private UserIdsProvider $userIdsProvider;

    /**
     * @var AuthenticatedUserProviderInterface
     */
    private AuthenticatedUserProviderInterface $userProvider;

    /**
     * @var CommandBusInterface
     */
    private CommandBusInterface $commandBus;

    /**
     * @param ProductRepositoryInterface         $productRepository
     * @param WorkflowProvider                   $workflowProvider
     * @param UserIdsProvider                    $userIdsProvider
     * @param AuthenticatedUserProviderInterface $userProvider
     * @param CommandBusInterface                $commandBus
     */
    public function __construct(
        ProductRepositoryInterface $productRepository,
        WorkflowProvider $workflowProvider,
        UserIdsProvider $userIdsProvider,
        AuthenticatedUserProviderInterface $userProvider,
        CommandBusInterface $commandBus
    ) {
        $this->productRepository = $productRepository;
        $this->workflowProvider = $workflowProvider;
        $this->userIdsProvider = $userIdsProvider;
        $this->userProvider = $userProvider;
        $this->commandBus = $commandBus;
    }

    /**
     * @param ProductValueChangedEvent $event
     *
     * @throws \Exception
     */
    public function __invoke(ProductValueChangedEvent $event): void
    {
        $attributeCode = $event->getAttributeCode();
        if ($attributeCode->getValue() === StatusSystemAttribute::CODE) {
            $workflow = $this->workflowProvider->provide();
            $source = new StatusCode($event->getFrom()->getValue());
            $destination = new StatusCode($event->getTo()->getValue());
            if ($workflow->hasTransition($source, $destination)) {
                $transition = $workflow->getTransition($source, $destination);
                if (!empty($transition->getRoleIds())) {
                    $productId = new ProductId($event->getAggregateId()->getValue());
                    $product = $this->productRepository->load($productId);
                    Assert::notNull($product);

                    $roleIds = $transition->getRoleIds();
                    $recipients = $this->userIdsProvider->getUserIds($roleIds);
                    $user = $this->userProvider->provide();

                    $notification = new StatusChangedNotification(
                        $product->getSku(),
                        $transition->getFrom(),
                        $transition->getTo(),
                        $user
                    );
                    $command = new SendNotificationCommand($notification, $recipients);

                    $this->commandBus->dispatch($command);
                }
            }
        }
    }
}
