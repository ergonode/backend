<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Workflow\Application\Controller\Api;

use Ergonode\Core\Application\Controller\AbstractApiController;
use Ergonode\Core\Application\Exception\FormValidationHttpException;
use Ergonode\Core\Domain\ValueObject\TranslatableString;
use Ergonode\Workflow\Application\Form\Model\WorkflowFormModel;
use Ergonode\Workflow\Application\Form\WorkflowForm;
use Ergonode\Workflow\Domain\Command\Workflow\CreateWorkflowCommand;
use Ergonode\Workflow\Domain\Command\Workflow\UpdateWorkflowCommand;
use Ergonode\Workflow\Domain\Provider\WorkflowProvider;
use Ergonode\Workflow\Domain\ValueObject\Status;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\PropertyAccess\Exception\InvalidPropertyPathException;
use Symfony\Component\Routing\Annotation\Route;

/**
 */
class WorkflowController extends AbstractApiController
{
    /**
     * @var WorkflowProvider
     */
    private $provider;

    /**
     * @var MessageBusInterface
     */
    private $messageBus;

    /**
     * @param WorkflowProvider    $provider
     * @param MessageBusInterface $messageBus
     */
    public function __construct(WorkflowProvider $provider, MessageBusInterface $messageBus)
    {
        $this->provider = $provider;
        $this->messageBus = $messageBus;
    }

    /**
     * @Route("/workflow/default", methods={"GET"})
     *
     * @IsGranted("WORKFLOW_READ")
     *
     * @SWG\Tag(name="Workflow")
     * @SWG\Parameter(
     *     name="language",
     *     in="path",
     *     type="string",
     *     required=true,
     *     default="EN",
     *     description="Language Code",
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Returns attribute",
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Not found",
     * )
     *
     * @return Response
     *
     * @throws \Exception
     */
    public function getWorkflow(): Response
    {
        $workflow = $this->provider->provide();

        if ($workflow) {
            return $this->createRestResponse($workflow);
        }

        throw new NotFoundHttpException();
    }

    /**
     * @Route("/workflow", methods={"POST"})
     *
     * @IsGranted("WORKFLOW_CREATE")
     *
     * @SWG\Tag(name="Workflow")
     * @SWG\Parameter(
     *     name="language",
     *     in="path",
     *     type="string",
     *     description="Language code",
     *     default="EN"
     * )
     * @SWG\Parameter(
     *     name="body",
     *     in="body",
     *     description="Add workflow",
     *     required=true,
     *     @SWG\Schema(ref="#/definitions/workflow")
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Returns attribute",
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Not found",
     * )
     *
     * @param Request $request
     *
     * @return Response
     *
     * @throws \Exception
     */
    public function createWorkflow(Request $request): Response
    {
        try {
            $model = new WorkflowFormModel();
            $form = $this->createForm(WorkflowForm::class, $model);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                /** @var WorkflowFormModel $data */
                $data = $form->getData();

                $statuses = [];
                foreach ($data->statuses as $status) {
                    $statuses[$status->code] = new Status(
                        $status->color,
                        new TranslatableString($status->name),
                        new TranslatableString($status->description)
                    );
                }

                $command = new CreateWorkflowCommand(
                    $data->code,
                    $statuses
                );

                $this->messageBus->dispatch($command);

                return $this->createRestResponse(['id' => $command->getId()], [], Response::HTTP_CREATED);
            }
        } catch (InvalidPropertyPathException $exception) {
            throw new BadRequestHttpException('Invalid JSON format');
        }

        throw new FormValidationHttpException($form);
    }

    /**
     * @Route("/workflow/default", methods={"PUT"})
     *
     * @IsGranted("WORKFLOW_UPDATE")
     *
     * @SWG\Tag(name="Workflow")
     * @SWG\Parameter(
     *     name="body",
     *     in="body",
     *     description="Add attribute",
     *     required=true,
     *     @SWG\Schema(ref="#/definitions/workflow")
     * )
     * @SWG\Parameter(
     *     name="language",
     *     in="path",
     *     type="string",
     *     required=true,
     *     default="EN",
     *     description="Language Code",
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Returns attribute",
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Not found",
     * )
     *
     * @param Request $request
     *
     * @return Response
     *
     * @throws \Exception
     */
    public function updateWorkflow(Request $request): Response
    {
        try {
            $workflow = $this->provider->provide();
            $model = new WorkflowFormModel();
            $form = $this->createForm(WorkflowForm::class, $model, ['method' => Request::METHOD_PUT]);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                /** @var WorkflowFormModel $data */
                $data = $form->getData();

                $statuses = [];
                foreach ($data->statuses as $status) {
                    $statuses[$status->code] = new Status(
                        $status->color,
                        new TranslatableString($status->name),
                        new TranslatableString($status->description)
                    );
                }

                $command = new UpdateWorkflowCommand(
                    $workflow->getId(),
                    $statuses
                );
                $this->messageBus->dispatch($command);

                return $this->createRestResponse(['id' => $command->getId()], [], Response::HTTP_CREATED);
            }
        } catch (InvalidPropertyPathException $exception) {
            throw new BadRequestHttpException('Invalid JSON format');
        }

        throw new FormValidationHttpException($form);
    }
}
