<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Importer\Application\Controller\Api\Import;

use Ergonode\Api\Application\Exception\FormValidationHttpException;
use Ergonode\Api\Application\Response\CreatedResponse;
use Ergonode\EventSourcing\Infrastructure\Bus\CommandBusInterface;
use Ergonode\Importer\Application\Form\UploadForm;
use Ergonode\Importer\Application\Model\Form\UploadModel;
use Ergonode\Importer\Application\Service\Upload\UploadServiceInterface;
use Ergonode\Importer\Domain\Command\CreateFileImportCommand;
use Ergonode\Reader\Domain\Entity\ReaderId;
use Ergonode\Transformer\Domain\Entity\TransformerId;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Swagger\Annotations as SWG;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("imports/upload", methods={"POST"})
 */
class ImportUploadAction
{
    /**
     * @var FormFactoryInterface
     */
    private FormFactoryInterface $formFactory;

    /**
     * @var UploadServiceInterface
     */
    private UploadServiceInterface $uploadService;

    /**
     * @var CommandBusInterface
     */
    private CommandBusInterface $commandBus;

    /**
     * @param FormFactoryInterface $formFactory
     * @param UploadServiceInterface $uploadService
     * @param CommandBusInterface $commandBus
     */
    public function __construct(
        FormFactoryInterface $formFactory,
        UploadServiceInterface $uploadService,
        CommandBusInterface $commandBus
    ) {
        $this->formFactory = $formFactory;
        $this->uploadService = $uploadService;
        $this->commandBus = $commandBus;
    }

    /**
     * @IsGranted("IMPORT_CREATE")
     *
     * @SWG\Tag(name="Importer")
     * @SWG\Parameter(
     *     name="upload",
     *     in="formData",
     *     type="file",
     *     description="The field used to upload file and create import",
     * )
     * @SWG\Parameter(
     *     name="language",
     *     in="path",
     *     type="string",
     *     required=true,
     *     default="EN",
     *     description="Language Code",
     * )
     * @SWG\Parameter(
     *     name="transformer",
     *     in="formData",
     *     type="string",
     *     description="Transformer generator id",
     * )
     * @SWG\Parameter(
     *     name="reader",
     *     in="formData",
     *     type="string",
     *     description="Reader Id",
     * )
     * @SWG\Parameter(
     *     name="action",
     *     in="formData",
     *     type="string",
     *     description="Processor action",
     * )
     * @SWG\Response(
     *     response=201,
     *     description="Returns import ID",
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Validation error",
     *     @SWG\Schema(ref="#/definitions/validation_error_response")
     * )
     *
     * @param Request $request
     *
     * @return Response
     *
     * @throws \Exception
     */
    public function __invoke(Request $request): Response
    {
        $uploadModel = new UploadModel();

        $form = $this->formFactory->create(UploadForm::class, $uploadModel);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadModel $data */
            $data = $form->getData();
            $file = $this->uploadService->upload($uploadModel->upload);
            $action = $data->action;
            $command = new CreateFileImportCommand(
                $uploadModel->upload->getClientOriginalName(),
                $file->getFilename(),
                new ReaderId($data->reader),
                new TransformerId($data->transformer),
                $action
            );
            $this->commandBus->dispatch($command);

            $response = new CreatedResponse($command->getId());
        } else {
            throw new FormValidationHttpException($form);
        }

        return $response;
    }
}
