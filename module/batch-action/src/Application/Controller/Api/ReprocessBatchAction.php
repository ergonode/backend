<?php
/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
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
use Ergonode\BatchAction\Application\Form\Model\BatchActionFormModel;
use Ergonode\BatchAction\Domain\ValueObject\BatchActionType;
use Ergonode\BatchAction\Domain\ValueObject\BatchActionFilterDisabled;
use Ergonode\BatchAction\Domain\Command\CreateBatchActionCommand;
use Ergonode\BatchAction\Domain\Entity\BatchActionId;
use Symfony\Component\PropertyAccess\Exception\InvalidPropertyPathException;
use Ergonode\Api\Application\Exception\FormValidationHttpException;
use Symfony\Component\Form\FormFactoryInterface;
use Ergonode\BatchAction\Application\Provider\BatchActionFormProvider;
use Ergonode\BatchAction\Application\Controller\Api\Factory\BatchActionFilterFactory;
use Ergonode\BatchAction\Infrastructure\Provider\BatchActionProcessorProvider;
use Ergonode\BatchAction\Domain\Command\ReprocessBatchActionCommand;

/**
 * @Route(
 *     name="ergonode_batch_action_reprocess",
 *     path="/batch-action/{action}/reprocess",
 *     methods={"PUT"},
 *     requirements={"action" = "[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}"}
 * )
 */
class ReprocessBatchAction
{
    private FormFactoryInterface $formFactory;

    private BatchActionFormProvider $formProvider;

    private CommandBusInterface $commandBus;

    public function __construct(
        FormFactoryInterface $formFactory,
        BatchActionFormProvider $formProvider,
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
     *     response=200,
     *     description="Returns batch information",
     * )
     *
     * @ParamConverter(class="Ergonode\BatchAction\Domain\Entity\BatchAction", name="action")
     */
    public function __invoke(BatchAction $action, Request $request): void
    {
        $type = $action->getType()->getValue();
        try {
            $form = $this->formFactory->create($this->formProvider->provide($type));
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                /** @var BatchActionFormModel $data */
                $data = $form->getData();

                $command = new ReprocessBatchActionCommand(
                    BatchActionId::generate(),
                    $data->payload ?: null,
                    $data->autoEndOnErrors
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
