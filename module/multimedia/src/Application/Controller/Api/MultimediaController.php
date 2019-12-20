<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Multimedia\Application\Controller\Api;

use Ergonode\Api\Application\Exception\FormValidationHttpException;
use Ergonode\Api\Application\Response\CreatedResponse;
use Ergonode\Multimedia\Application\Form\MultimediaUploadForm;
use Ergonode\Multimedia\Application\Model\MultimediaUploadModel;
use Ergonode\Multimedia\Domain\Command\UploadMultimediaCommand;
use Ergonode\Multimedia\Domain\Entity\Multimedia;
use Ergonode\Multimedia\Domain\Repository\MultimediaRepositoryInterface;
use Ergonode\Multimedia\Infrastructure\Provider\MultimediaFileProviderInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Swagger\Annotations as SWG;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 */
class MultimediaController extends AbstractController
{
    /**
     * @var MultimediaFileProviderInterface
     */
    private $fileProvider;

    /**
     * @var MessageBusInterface
     */
    private $messageBus;

    /**
     * @var MultimediaRepositoryInterface
     */
    private $multimediaRepository;


    /**
     * @param MultimediaFileProviderInterface $fileProvider
     * @param MessageBusInterface             $messageBus
     * @param MultimediaRepositoryInterface   $multimediaRepository
     */
    public function __construct(
        MultimediaFileProviderInterface $fileProvider,
        MessageBusInterface $messageBus,
        MultimediaRepositoryInterface $multimediaRepository
    ) {
        $this->fileProvider = $fileProvider;
        $this->messageBus = $messageBus;
        $this->multimediaRepository = $multimediaRepository;
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
            $command = new UploadMultimediaCommand('Default', $uploadModel->upload);
            if (!$this->multimediaRepository->exists($command->getId())) {
                $this->messageBus->dispatch($command);
            }

            $response = new CreatedResponse($command->getId());
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
