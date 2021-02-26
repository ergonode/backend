<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Importer\Application\Controller\Api\Import;

use Ergonode\Api\Application\Exception\FormValidationHttpException;
use Ergonode\Api\Application\Response\CreatedResponse;
use Ergonode\SharedKernel\Domain\Bus\CommandBusInterface;
use Ergonode\Importer\Application\Form\UploadForm;
use Ergonode\Importer\Application\Model\Form\UploadModel;
use Ergonode\Importer\Application\Service\Upload\UploadServiceInterface;
use Ergonode\SharedKernel\Domain\Aggregate\ImportId;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Swagger\Annotations as SWG;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Ergonode\Importer\Domain\Command\Import\UploadFileCommand;
use Ergonode\Importer\Domain\Entity\Source\AbstractSource;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * @Route(
 *     name="ergonode_import_upload",
 *     path="sources/{source}/upload",
 *     methods={"POST"},
 *     requirements={
 *          "source" = "[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}"
 *     }
 * )
 */
class ImportUploadAction
{
    private FormFactoryInterface $formFactory;

    private UploadServiceInterface $uploadService;

    private CommandBusInterface $commandBus;

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
     * @IsGranted("IMPORT_POST")
     *
     * @SWG\Tag(name="Import")
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
     *     default="en_GB",
     *     description="Language Code",
     * )
     * @SWG\Parameter(
     *     name="source_id",
     *     in="path",
     *     type="string",
     *     description="Source Id",
     * )
     * @SWG\Response(
     *     response=201,
     *     description="Returns import Id",
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Validation error",
     *     @SWG\Schema(ref="#/definitions/validation_error_response")
     * )
     *
     *
     * @ParamConverter(class="Ergonode\Importer\Domain\Entity\Source\AbstractSource")
     *
     *
     * @throws \Exception
     */
    public function __invoke(AbstractSource $source, Request $request): Response
    {
        $uploadModel = new UploadModel();

        $form = $this->formFactory->create(UploadForm::class, $uploadModel);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $file = $this->uploadService->upload($uploadModel->upload);
            $command = new UploadFileCommand(
                ImportId::generate(),
                $source->getId(),
                $file->getFilename(),
            );
            $this->commandBus->dispatch($command);

            return new CreatedResponse($command->getId());
        }

        throw new FormValidationHttpException($form);
    }
}
