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
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Ergonode\Multimedia\Application\Model\MultimediaUploadModel;
use Ergonode\Multimedia\Application\Form\MultimediaUploadForm;
use Ergonode\Api\Application\Response\CreatedResponse;
use Ergonode\Multimedia\Domain\Command\AddMultimediaCommand;
use Ergonode\SharedKernel\Domain\Aggregate\MultimediaId;
use Ergonode\Multimedia\Domain\Query\MultimediaQueryInterface;
use Ergonode\Multimedia\Infrastructure\Service\HashCalculationServiceInterface;
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
    private MultimediaQueryInterface $query;

    private HashCalculationServiceInterface $hashService;

    private FormFactoryInterface $formFactory;

    private CommandBusInterface $commandBus;

    public function __construct(
        MultimediaQueryInterface $query,
        HashCalculationServiceInterface $hashService,
        FormFactoryInterface $formFactory,
        CommandBusInterface $commandBus
    ) {
        $this->query = $query;
        $this->hashService = $hashService;
        $this->formFactory = $formFactory;
        $this->commandBus = $commandBus;
    }

    /**
     * @IsGranted("MULTIMEDIA_POST")
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
     *
     *
     * @throws \Exception
     */
    public function __invoke(Request $request): Response
    {
        $uploadModel = new MultimediaUploadModel();

        $form = $this->formFactory->create(MultimediaUploadForm::class, $uploadModel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $hash = $this->hashService->calculateHash($uploadModel->upload);
            if ($this->query->fileExists($hash)) {
                $response = new CreatedResponse($this->query->findIdByHash($hash));
            } else {
                $command = new AddMultimediaCommand(MultimediaId::generate(), $uploadModel->upload);
                $this->commandBus->dispatch($command);
                $response = new CreatedResponse($command->getId());
            }
        } else {
            throw new FormValidationHttpException($form);
        }

        return $response;
    }
}
