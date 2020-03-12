<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Multimedia\Application\Controller\Api\Multimedia;

use Ergonode\Api\Application\Exception\FormValidationHttpException;
use Ergonode\Api\Application\Response\CreatedResponse;
use Ergonode\EventSourcing\Infrastructure\Bus\CommandBusInterface;
use Ergonode\Multimedia\Application\Form\MultimediaUploadForm;
use Ergonode\Multimedia\Application\Model\MultimediaUploadModel;
use Ergonode\Multimedia\Domain\Command\AddMultimediaCommand;
use Ergonode\Multimedia\Domain\Entity\Multimedia;
use Ergonode\Multimedia\Domain\Query\MultimediaQueryInterface;
use Ergonode\Multimedia\Infrastructure\Provider\MultimediaFileProviderInterface;
use Ergonode\Multimedia\Infrastructure\Service\HashCalculationServiceInterface;
use Ergonode\SharedKernel\Domain\Aggregate\MultimediaId;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Swagger\Annotations as SWG;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 */
class MultimediaController extends AbstractController
{
    /**
     * @var MultimediaFileProviderInterface
     */
    private MultimediaFileProviderInterface $fileProvider;

    /**
     * @var MultimediaQueryInterface
     */
    private MultimediaQueryInterface $query;

    /**
     * @var HashCalculationServiceInterface
     */
    private HashCalculationServiceInterface $hashService;

    /**
     * @var CommandBusInterface
     */
    private CommandBusInterface $commandBus;

    /**
     * @param MultimediaFileProviderInterface $fileProvider
     * @param MultimediaQueryInterface        $query
     * @param HashCalculationServiceInterface $hashService
     * @param CommandBusInterface             $commandBus
     */
    public function __construct(
        MultimediaFileProviderInterface $fileProvider,
        MultimediaQueryInterface $query,
        HashCalculationServiceInterface $hashService,
        CommandBusInterface $commandBus
    ) {
        $this->fileProvider = $fileProvider;
        $this->query = $query;
        $this->hashService = $hashService;
        $this->commandBus = $commandBus;
    }

    /**
     * @Route("/upload", methods={"POST"})
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
     * @param Request $request
     *
     * @return Response
     *
     * @throws \Exception
     */
    public function uploadMultimedia(Request $request): Response
    {
        $uploadModel = new MultimediaUploadModel();

        $form = $this->createForm(MultimediaUploadForm::class, $uploadModel);
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

    /**
     * @Route("/{multimedia}", methods={"get"})
     *
     * @SWG\Tag(name="Multimedia")
     * @SWG\Parameter(
     *     name="multimedia",
     *     in="path",
     *     type="string",
     *     description="Multimedia id",
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Returns multimedia file",
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Not found",
     * )
     *
     * @param Multimedia $multimedia
     *
     * @ParamConverter(class="Ergonode\Multimedia\Domain\Entity\Multimedia")
     *
     * @return Response
     *
     * @throws \Exception
     */
    public function getMultimedia(Multimedia $multimedia): Response
    {
        $file = $this->fileProvider->getFile($multimedia);

        return $this->file($file);
    }
}
