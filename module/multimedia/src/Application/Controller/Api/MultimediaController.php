<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See license.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Multimedia\Application\Controller\Api;

use Ergonode\Core\Application\Exception\FormValidationHttpException;
use Ergonode\Core\Application\Response\EmptyResponse;
use Ergonode\Multimedia\Application\Form\MultimediaUploadForm;
use Ergonode\Multimedia\Application\Model\MultimediaUploadModel;
use Ergonode\Multimedia\Domain\Command\UploadMultimediaCommand;
use Ergonode\Multimedia\Domain\Entity\Multimedia;
use Ergonode\Multimedia\Infrastructure\Provider\MultimediaFileProviderInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
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
     * @param MultimediaFileProviderInterface $fileProvider
     * @param MessageBusInterface             $messageBus
     */
    public function __construct(MultimediaFileProviderInterface $fileProvider, MessageBusInterface $messageBus)
    {
        $this->fileProvider = $fileProvider;
        $this->messageBus = $messageBus;
    }

    /**
     * @Route("/upload", methods={"POST"})
     *
     * @IsGranted("MULTIMEDIA_CREATE")
     *
     * @SWG\Tag(name="Multimedia")
     * @SWG\Parameter(
     *     name="upload",
     *     in="formData",
     *     type="file",
     *     description="The field used to upload multimedia",
     * )
     * @SWG\Response(
     *     response=204,
     *     description="Success",
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Unsupported file type or file size",
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
            $command = new UploadMultimediaCommand('TestName', $uploadModel->upload);
            $this->messageBus->dispatch($command);

            $response = new EmptyResponse($command->getId()->getValue());
        } else {
            throw new FormValidationHttpException($form);
        }

        return $response;
    }

    /**
     * @Route("/{multimedia}", methods={"get"})
     *
     * @IsGranted("MULTIMEDIA_READ")
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
     *     response=400,
     *     description="Bad request",
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
