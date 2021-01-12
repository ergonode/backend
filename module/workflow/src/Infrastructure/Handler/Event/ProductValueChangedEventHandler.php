<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Infrastructure\Handler\Event;

use Ergonode\Account\Infrastructure\Provider\AuthenticatedUserProviderInterface;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\EventSourcing\Infrastructure\Bus\CommandBusInterface;
use Ergonode\Notification\Domain\Command\SendNotificationCommand;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use Ergonode\Product\Domain\Event\ProductValueChangedEvent;
use Ergonode\Product\Domain\Repository\ProductRepositoryInterface;
use Ergonode\Value\Domain\ValueObject\ValueInterface;
use Ergonode\Workflow\Domain\Entity\AbstractWorkflow;
use Ergonode\Workflow\Domain\Entity\Attribute\StatusSystemAttribute;
use Ergonode\Workflow\Domain\Entity\Status;
use Ergonode\Workflow\Domain\Notification\StatusChangedNotification;
use Ergonode\Workflow\Domain\Query\ProductWorkflowStatusQueryInterface;
use Ergonode\Workflow\Domain\Repository\StatusRepositoryInterface;
use Ergonode\Workflow\Domain\ValueObject\StatusCode;
use Ergonode\Workflow\Infrastructure\Provider\UserIdsProvider;
use Webmozart\Assert\Assert;
use Ergonode\Workflow\Domain\Provider\WorkflowProvider;
use Ergonode\SharedKernel\Domain\Aggregate\StatusId;

class ProductValueChangedEventHandler
{
    private ProductRepositoryInterface $productRepository;

    private WorkflowProvider $workflowProvider;

    private UserIdsProvider $userIdsProvider;

    private AuthenticatedUserProviderInterface $userProvider;

    private StatusRepositoryInterface $statusRepository;

    private CommandBusInterface $commandBus;

    private ProductWorkflowStatusQueryInterface $productWorkflowQuery;

    public function __construct(
        ProductRepositoryInterface $productRepository,
        WorkflowProvider $workflowProvider,
        UserIdsProvider $userIdsProvider,
        AuthenticatedUserProviderInterface $userProvider,
        StatusRepositoryInterface $statusRepository,
        CommandBusInterface $commandBus,
        ProductWorkflowStatusQueryInterface $productWorkflowQuery
    ) {
        $this->productRepository = $productRepository;
        $this->workflowProvider = $workflowProvider;
        $this->userIdsProvider = $userIdsProvider;
        $this->userProvider = $userProvider;
        $this->statusRepository = $statusRepository;
        $this->commandBus = $commandBus;
        $this->productWorkflowQuery = $productWorkflowQuery;
    }

    /**
     * @throws \Exception
     */
    public function __invoke(ProductValueChangedEvent $event): void
    {
        $attributeCode = $event->getAttributeCode();
        if ($attributeCode->getValue() === StatusSystemAttribute::CODE) {
            $workflow = $this->workflowProvider->provide();

            $statuses = $this->productWorkflowQuery->getStatuses($event->getAggregateId());
            $languages = $this->getLanguages($statuses, $event->getTo());
            foreach ($languages as $language) {
                $from = $statuses[$language->getCode()];
                $to = $event->getTo()->getValue()[$language->getCode()];
                $source = new StatusId($from);
                $destination = new StatusId($to);
                if ($workflow->hasTransition($source, $destination)) {
                    $this->sendNotificationCommand(
                        $workflow,
                        $source,
                        $destination,
                        $event->getAggregateId(),
                        $language
                    );
                }
            }
        }
    }

    /**
     * @throws \Exception
     */
    private function sendNotificationCommand(
        AbstractWorkflow $workflow,
        StatusId $source,
        StatusId $destination,
        ProductId $productId,
        ?Language $language = null
    ): void {
        $transition = $workflow->getTransition($source, $destination);
        if (!empty($transition->getRoleIds())) {
            $product = $this->productRepository->load($productId);
            Assert::notNull($product);

            $roleIds = $transition->getRoleIds();
            $recipients = $this->userIdsProvider->getUserIds($roleIds);
            $user = $this->userProvider->provide();

            $notification = new StatusChangedNotification(
                $product->getSku(),
                $this->getStatusCode($transition->getFrom()),
                $this->getStatusCode($transition->getTo()),
                $user,
                $language
            );
            $command = new SendNotificationCommand($notification, $recipients);

            $this->commandBus->dispatch($command);
        }
    }

    /**
     * @throws \Exception
     */
    private function getStatusCode(StatusId $statusId): StatusCode
    {
        $status = $this->statusRepository->load($statusId);
        Assert::isInstanceOf($status, Status::class, 'x');

        return $status->getCode();
    }

    /**
     * @return Language[]
     */
    private function getLanguages(array $from, ValueInterface $to): array
    {
        $result = [];
        $languages = array_keys(array_diff($to->getValue(), $from));

        foreach ($languages as $language) {
            $result[] = new Language($language);
        }

        return $result;
    }
}
