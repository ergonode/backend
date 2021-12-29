<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\BatchAction\Application\Controller\Api;

use Symfony\Component\Routing\Annotation\Route;
use Ergonode\SharedKernel\Domain\Bus\CommandBusInterface;
use Swagger\Annotations as SWG;
use Ergonode\BatchAction\Domain\Entity\BatchAction;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PropertyAccess\Exception\InvalidPropertyPathException;
use Ergonode\Api\Application\Exception\FormValidationHttpException;
use Symfony\Component\Form\FormFactoryInterface;
use Ergonode\BatchAction\Domain\Command\ReprocessBatchActionCommand;
use Ergonode\BatchAction\Application\Provider\BatchActionReprocessFormProvider;
use Ergonode\BatchAction\Application\Form\Model\BatchActionReprocessFormModel;
use Ergonode\BatchAction\Domain\ValueObject\BatchActionStatus;

/**
 * @Route(
 *     name="ergonode_batch_action_reprocess",
 *     path="/batch-action/{action}/reprocess",
 *     methods={"PATCH"},
 *     requirements={"action" = "[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}"}
 * )
 */
class ReprocessBatchAction
{
    private FormFactoryInterface $formFactory;

    private BatchActionReprocessFormProvider $formProvider;

    private CommandBusInterface $commandBus;

    public function __construct(
        FormFactoryInterface $formFactory,
        BatchActionReprocessFormProvider $formProvider,
        CommandBusInterface $commandBus
    ) {
        $this->formFactory = $formFactory;
        $this->formProvider = $formProvider;
        $this->commandBus = $commandBus;
    }

    /**
     * @SWG\Tag(name="Batch action")
     * @SWG\Parameter(
     *     name="language",
     *     in="path",
     *     type="string",
     *     description="Language code",
     *     default="en_GB"
     * )
     * @SWG\Parameter(
     *     name="action",
     *     in="path",
     *     type="string",
     *     description="Batch action id",
     * )
     * @SWG\Response(
     *     response=204,
     *     description="Successful reprocessing submit",
     * )
     *
     * @ParamConverter(class="Ergonode\BatchAction\Domain\Entity\BatchAction", name="action")
     */
    public function __invoke(BatchAction $action, Request $request): void
    {
        $type = $action->getType()->getValue();

        if (!$action->getStatus()->isWaitingForDecision()) {
            throw new BadRequestHttpException(
                sprintf(
                    'Only batch action in status %s can be reprocessed',
                    [BatchActionStatus::WAITING_FOR_DECISION]
                )
            );
        }

        try {
            $form = $this->formFactory->create(
                $this->formProvider->provide($type),
                null,
                ['method' => Request::METHOD_PATCH]
            );
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                /** @var BatchActionReprocessFormModel $data */
                $data = $form->getData();

                $command = new ReprocessBatchActionCommand(
                    $action->getId(),
                    $data->autoEndOnErrors?: $action->isAutoEndOnErrors(),
                    $data->payload ?: $action->getPayload(),
                );

                $this->commandBus->dispatch($command);

                return;
            }
        } catch (InvalidPropertyPathException $exception) {
            throw new BadRequestHttpException('Invalid JSON format');
        }

        throw new FormValidationHttpException($form);
    }
}
