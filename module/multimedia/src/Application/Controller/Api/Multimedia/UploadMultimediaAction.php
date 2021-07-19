<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Multimedia\Application\Controller\Api\Multimedia;

use Ergonode\Api\Application\Exception\FormValidationHttpException;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Ergonode\Multimedia\Application\Model\MultimediaUploadModel;
use Ergonode\Multimedia\Application\Form\MultimediaUploadForm;
use Ergonode\Multimedia\Domain\Command\AddMultimediaCommand;
use Ergonode\SharedKernel\Domain\Aggregate\MultimediaId;
use Ergonode\SharedKernel\Domain\Bus\CommandBusInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route(
 *     name="ergonode_multimedia_upload",
 *     path="/multimedia/upload",
 *     methods={"POST"},
 * )
 */
class UploadMultimediaAction
{
    private FormFactoryInterface $formFactory;

    private CommandBusInterface $commandBus;

    public function __construct(
        FormFactoryInterface $formFactory,
        CommandBusInterface $commandBus
    ) {
        $this->formFactory = $formFactory;
        $this->commandBus = $commandBus;
    }

    /**
     * @IsGranted("ERGONODE_ROLE_MULTIMEDIA_POST")
     *
     * @SWG\Tag(name="Multimedia")
     * @SWG\Parameter(
     *     name="upload",
     *     in="formData",
     *     type="file",
     *     description="The field used to upload multimedia",
     * )
     * @SWG\Response(
     *     response=201,
     *     description="Returns multimedia ID",
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Validation error",
     *     @SWG\Schema(ref="#/definitions/validation_error_response")
     * )
     *
     * @throws \Exception
     */
    public function __invoke(Request $request): MultimediaId
    {
        $uploadModel = new MultimediaUploadModel();

        $form = $this->formFactory->create(MultimediaUploadForm::class, $uploadModel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $command = new AddMultimediaCommand(
                MultimediaId::generate(),
                $uploadModel->upload,
                $uploadModel->upload->getClientOriginalName(),
            );
            $this->commandBus->dispatch($command);
            $id = $command->getId();
        } else {
            throw new FormValidationHttpException($form);
        }

        return $id;
    }
}
