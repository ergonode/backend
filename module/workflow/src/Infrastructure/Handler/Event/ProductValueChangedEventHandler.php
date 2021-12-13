<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Infrastructure\Handler\Event;

use Ergonode\Account\Infrastructure\Provider\AuthenticatedUserProviderInterface;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\SharedKernel\Domain\Bus\CommandBusInterface;
use Ergonode\Notification\Domain\Command\SendNotificationCommand;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use Ergonode\Product\Domain\Event\ProductValueChangedEvent;
use Ergonode\Product\Domain\Repository\ProductRepositoryInterface;
use Ergonode\Value\Domain\ValueObject\ValueInterface;
use Ergonode\Workflow\Domain\Entity\AbstractWorkflow;
use Ergonode\Workflow\Domain\Entity\Attribute\StatusSystemAttribute;
use Ergonode\Workflow\Domain\Entity\Status;
use Ergonode\Workflow\Domain\Notification\StatusChangedNotification;
use Ergonode\Workflow\Domain\Repository\StatusRepositoryInterface;
use Ergonode\Workflow\Domain\ValueObject\StatusCode;
use Ergonode\Workflow\Infrastructure\Provider\UserIdsProvider;
use Webmozart\Assert\Assert;
use Ergonode\Workflow\Domain\Provider\WorkflowProviderInterface;
use Ergonode\SharedKernel\Domain\Aggregate\StatusId;

class ProductValueChangedEventHandler
{
    private ProductRepositoryInterface $productRepository;

    private WorkflowProviderInterface $workflowProvider;

    private UserIdsProvider $userIdsProvider;

    private AuthenticatedUserProviderInterface $userProvider;

    private StatusRepositoryInterface $statusRepository;

    private CommandBusInterface $commandBus;

    public function __construct(
        ProductRepositoryInterface $productRepository,
        WorkflowProviderInterface $workflowProvider,
        UserIdsProvider $userIdsProvider,
        AuthenticatedUserProviderInterface $userProvider,
        StatusRepositoryInterface $statusRepository,
        CommandBusInterface $commandBus
    ) {
        $this->productRepository = $productRepository;
        $this->workflowProvider = $workflowProvider;
        $this->userIdsProvider = $userIdsProvider;
        $this->userProvider = $userProvider;
        $this->statusRepository = $statusRepository;
        $this->commandBus = $commandBus;
    }

    /**
     * @throws \Exception
     */
    public function __invoke(ProductValueChangedEvent $event): void
    {
        $attributeCode = $event->getAttributeCode();
        if ($attributeCode->getValue() === StatusSystemAttribute::CODE) {
            $languages = $this->getLanguages($event->getFrom(), $event->getTo());
            foreach ($languages as $language) {
                $workflow = $this->workflowProvider->provide($language);
                $from = $workflow->getDefaultStatus();
                if (isset($event->getFrom()->getValue()[$language->getCode()])) {
                    $from = new StatusId($event->getFrom()->getValue()[$language->getCode()]);
                }

                $to = new StatusId($event->getTo()->getValue()[$language->getCode()]);
                if ($workflow->hasTransition($from, $to)) {
                    $this->sendNotificationCommand(
                        $workflow,
                        $from,
                        $to,
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
        StatusId $from,
        StatusId $to,
        ProductId $productId,
        ?Language $language = null
    ): void {
        $roleIds = $workflow->getTransitionRoleIds($from, $to);
        if (!empty($roleIds)) {
            $product = $this->productRepository->load($productId);
            Assert::notNull($product);

            $recipients = $this->userIdsProvider->getUserIds($roleIds);
            $user = $this->userProvider->provide();

            $notification = new StatusChangedNotification(
                $productId,
                $product->getSku(),
                $this->getStatusCode($from),
                $this->getStatusCode($to),
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
    private function getLanguages(ValueInterface $from, ValueInterface $to): array
    {
        $languages = array_keys(array_diff_assoc($to->getValue(), $from->getValue()));
        $result = [];
        foreach ($languages as &$language) {
            $result[] = new Language($language);
        }

        return $result;
    }
}
