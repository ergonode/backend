<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Multimedia\Application\Controller\Api\Avatar;

use Ergonode\Api\Application\Exception\FormValidationHttpException;
use Ergonode\Api\Application\Response\CreatedResponse;
use Ergonode\EventSourcing\Infrastructure\Bus\CommandBusInterface;
use Ergonode\Multimedia\Application\Form\AvatarUploadForm;
use Ergonode\Multimedia\Application\Model\AvatarUploadModel;
use Ergonode\Multimedia\Domain\Command\AddAvatarCommand;
use Ergonode\Multimedia\Domain\Query\AvatarQueryInterface;
use Ergonode\Multimedia\Infrastructure\Service\HashCalculationServiceInterface;
use Ergonode\SharedKernel\Domain\Aggregate\AvatarId;
use Swagger\Annotations as SWG;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route(
 *     name="ergonode_avatar_upload",
 *     path="/avatar/upload",
 *     methods={"POST"},
 * )
 */
class UploadAvatarAction
{
    /**
     * @var AvatarQueryInterface
     */
    private AvatarQueryInterface $query;

    /**
     * @var HashCalculationServiceInterface
     */
    private HashCalculationServiceInterface $hashService;

    /**
     * @var FormFactoryInterface
     */
    private FormFactoryInterface $formFactory;

    /**
     * @var CommandBusInterface
     */
    private CommandBusInterface $commandBus;

    /**
     * @param AvatarQueryInterface            $query
     * @param HashCalculationServiceInterface $hashService
     * @param FormFactoryInterface            $formFactory
     * @param CommandBusInterface             $commandBus
     */
    public function __construct(
        AvatarQueryInterface $query,
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
     * @SWG\Tag(name="Avatar")
     * @SWG\Parameter(
     *     name="upload",
     *     in="formData",
     *     type="file",
     *     description="The field used to upload avatar",
     * )
     * @SWG\Response(
     *     response=201,
     *     description="Returns avatar ID",
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
        $uploadModel = new AvatarUploadModel();

        $form = $this->formFactory->create(AvatarUploadForm::class, $uploadModel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $hash = $this->hashService->calculateHash($uploadModel->upload);
            if ($this->query->fileExists($hash)) {
                $response = new CreatedResponse($this->query->findIdByHash($hash));
            } else {
                $command = new AddAvatarCommand(AvatarId::generate(), $uploadModel->upload);
                $this->commandBus->dispatch($command);
                $response = new CreatedResponse($command->getId());
            }
        } else {
            throw new FormValidationHttpException($form);
        }

        return $response;
    }
}
