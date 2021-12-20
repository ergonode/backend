<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Workflow\Application\Controller\Api\Status;

use Ergonode\Api\Application\Exception\FormValidationHttpException;
use Ergonode\SharedKernel\Domain\Aggregate\StatusId;
use Ergonode\SharedKernel\Domain\Bus\CommandBusInterface;
use Ergonode\Workflow\Application\Form\Model\StatusOrderSetFormModel;
use Ergonode\Workflow\Application\Form\StatusOrderSetForm;
use Ergonode\Workflow\Domain\Command\Status\SetStatusOrderCommand;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\PropertyAccess\Exception\InvalidPropertyPathException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Swagger\Annotations as SWG;
use Symfony\Component\Form\FormFactoryInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route(
 *     name="ergonode_workflow_status_order",
 *     path="/status/order",
 *     methods={"POST"}
 * )
 */
class StatusOrderAction
{
    private CommandBusInterface $commandBus;

    private FormFactoryInterface $formFactory;

    public function __construct(CommandBusInterface $commandBus, FormFactoryInterface $formFactory)
    {
        $this->commandBus = $commandBus;
        $this->formFactory = $formFactory;
    }

    /**
     * @IsGranted("ERGONODE_ROLE_WORKFLOW_POST_STATUS_ORDER")
     *
     * @SWG\Tag(name="Workflow")
     * @SWG\Parameter(
     *     name="language",
     *     in="path",
     *     type="string",
     *     description="Language code",
     *     default="en_GB"
     * )
     * @SWG\Parameter(
     *     name="body",
     *     in="body",
     *     description="Set status ids order",
     *     required=true,
     *     @SWG\Schema(ref="#/definitions/status")
     * )
     * @SWG\Response(
     *     response=204,
     *     description="Success"
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Validation error",
     *     @SWG\Schema(ref="#/definitions/validation_error_response")
     * )
     *
     * @throws \Exception
     */
    public function __invoke(Request $request): void
    {
        try {
            $model = new StatusOrderSetFormModel();
            $form = $this->formFactory->create(StatusOrderSetForm::class, $model);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                /** @var StatusOrderSetFormModel $data */
                $data = $form->getData();
                $statusIds = array_map(function ($item) {
                    return new StatusId($item);
                }, $data->statusIds);

                $command = new SetStatusOrderCommand($statusIds);

                $this->commandBus->dispatch($command);
            } else {
                throw new FormValidationHttpException($form);
            }
        } catch (InvalidPropertyPathException $exception) {
            throw new BadRequestHttpException('Invalid JSON format');
        }
    }
}
