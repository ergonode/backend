<?php
/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\BatchAction\Application\Controller\Api;

use Ergonode\BatchAction\Application\Controller\Api\Factory\BatchActionFilterFactory;
use Ergonode\BatchAction\Application\Provider\BatchActionFormProvider;
use Ergonode\BatchAction\Domain\ValueObject\BatchActionFilterDisabled;
use Ergonode\BatchAction\Infrastructure\Provider\BatchActionProcessorProvider;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\FormFactoryInterface;
use Ergonode\SharedKernel\Domain\Bus\CommandBusInterface;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Ergonode\Api\Application\Response\CreatedResponse;
use Ergonode\BatchAction\Domain\Command\CreateBatchActionCommand;
use Ergonode\BatchAction\Domain\Entity\BatchActionId;
use Ergonode\BatchAction\Domain\ValueObject\BatchActionType;
use Ergonode\BatchAction\Application\Form\Model\BatchActionFormModel;
use Symfony\Component\PropertyAccess\Exception\InvalidPropertyPathException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Ergonode\Api\Application\Exception\FormValidationHttpException;

/**
 * @Route("/batch-action", methods={"POST"})
 */
class CreateBatchAction
{
    private FormFactoryInterface $formFactory;

    private BatchActionFormProvider $formProvider;

    private BatchActionFilterFactory $factory;

    private BatchActionProcessorProvider $processorProvider;

    private CommandBusInterface $commandBus;

    public function __construct(
        FormFactoryInterface $formFactory,
        BatchActionFormProvider $formProvider,
        BatchActionFilterFactory $factory,
        BatchActionProcessorProvider $processorProvider,
        CommandBusInterface $commandBus
    ) {
        $this->formFactory = $formFactory;
        $this->formProvider = $formProvider;
        $this->factory = $factory;
        $this->processorProvider = $processorProvider;
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
     *     name="body",
     *     in="body",
     *     description="Add batch action",
     *     required=true,
     *     @SWG\Schema(ref="#/definitions/batch-action")
     * )
     * @SWG\Response(
     *     response=201,
     *     description="Returns batch action ID",
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Validation error",
     *     @SWG\Schema(ref="#/definitions/validation_error_response")
     * )
     * @throws \Exception
     */
    public function __invoke(Request $request): Response
    {
        $type = $request->request->get('type', 'default');
        try {
            $form = $this->formFactory->create($this->formProvider->provide($type));
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                /** @var BatchActionFormModel $data */
                $data = $form->getData();

                $actionType = new BatchActionType($data->type);

                if (!$this->processorProvider->supports($actionType)) {
                    throw new BadRequestHttpException("Unsupported type {$data->type}");
                }

                $filter = 'all' === $data->filter ?
                    new BatchActionFilterDisabled() :
                    $this->factory->create($data->filter);

                $command = new CreateBatchActionCommand(
                    BatchActionId::generate(),
                    $actionType,
                    $filter,
                    $data->payload ?: null
                );

                $this->commandBus->dispatch($command);

                return new CreatedResponse($command->getId());
            }
        } catch (InvalidPropertyPathException $exception) {
            throw new BadRequestHttpException('Invalid JSON format');
        }

        throw new FormValidationHttpException($form);
    }
}
