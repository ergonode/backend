<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Infrastructure\Handler\Event;

use Ergonode\Account\Infrastructure\Provider\AuthenticatedUserProviderInterface;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\EventSourcing\Infrastructure\Bus\CommandBusInterface;
use Ergonode\Notification\Domain\Command\SendNotificationCommand;
use Ergonode\SharedKernel\Domain\Aggregate\ProductId;
use Ergonode\Product\Domain\Event\ProductValueChangedEvent;
use Ergonode\Product\Domain\Repository\ProductRepositoryInterface;
use Ergonode\Value\Domain\ValueObject\ValueInterface;
use Ergonode\Workflow\Application\Controller\Api\Status\StatusReadAction;
use Ergonode\Workflow\Domain\Entity\AbstractWorkflow;
use Ergonode\Workflow\Domain\Entity\Attribute\StatusSystemAttribute;
use Ergonode\Workflow\Domain\Entity\Status;
use Ergonode\Workflow\Domain\Notification\StatusChangedNotification;
use Ergonode\Workflow\Domain\Repository\StatusRepositoryInterface;
use Ergonode\Workflow\Domain\ValueObject\StatusCode;
use Ergonode\Workflow\Infrastructure\Provider\UserIdsProvider;
use Webmozart\Assert\Assert;
use Ergonode\Workflow\Domain\Provider\WorkflowProvider;
use Ergonode\SharedKernel\Domain\Aggregate\StatusId;

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
     * @var StatusRepositoryInterface
     */
    private StatusRepositoryInterface $statusRepository;

    /**
     * @var CommandBusInterface
     */
    private CommandBusInterface $commandBus;

    /**
     * @param ProductRepositoryInterface         $productRepository
     * @param WorkflowProvider                   $workflowProvider
     * @param UserIdsProvider                    $userIdsProvider
     * @param AuthenticatedUserProviderInterface $userProvider
     * @param StatusRepositoryInterface          $statusRepository
     * @param CommandBusInterface                $commandBus
     */
    public function __construct(
        ProductRepositoryInterface $productRepository,
        WorkflowProvider $workflowProvider,
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
     * @param ProductValueChangedEvent $event
     *
     * @throws \Exception
     */
    public function __invoke(ProductValueChangedEvent $event): void
    {
        $attributeCode = $event->getAttributeCode();
        if ($attributeCode->getValue() === StatusSystemAttribute::CODE) {
            $workflow = $this->workflowProvider->provide();

            $languages = $this->getLanguages($event->getFrom(), $event->getTo());
            foreach ($languages as $language) {
                $from = $event->getFrom()->getValue()[$language->getCode()];
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
     * @param AbstractWorkflow $workflow
     * @param StatusId         $source
     * @param StatusId         $destination
     * @param ProductId        $productId
     * @param Language|null    $language
     *
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
     * @param StatusId $statusId
     *
     * @return StatusCode
     *
     * @throws \Exception
     */
    private function getStatusCode(StatusId $statusId): StatusCode
    {
        $status = $this->statusRepository->load($statusId);
        Assert::isInstanceOf($status, Status::class, 'x');

        return $status->getCode();
    }

    /**
     * @param ValueInterface $from
     * @param ValueInterface $to
     *
     * @return Language[]
     */
    private function getLanguages(ValueInterface $from, ValueInterface $to): array
    {
        $languages = array_keys(array_diff($to->getValue(), $from->getValue()));

        foreach ($languages as &$language) {
            $language = new Language($language);
        }

        return $languages;
    }
}
