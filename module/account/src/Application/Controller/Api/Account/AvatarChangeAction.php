<?php

/**
 * Copyright © Ergonode Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace Ergonode\Account\Application\Controller\Api\Account;

use Ergonode\Account\Application\Form\AvatarUploadForm;
use Ergonode\Account\Application\Form\Model\AvatarUploadModel;
use Ergonode\Account\Domain\Command\User\ChangeUserAvatarCommand;
use Ergonode\Account\Domain\Entity\User;
use Ergonode\Api\Application\Exception\FormValidationHttpException;
use Ergonode\Api\Application\Response\EmptyResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Swagger\Annotations as SWG;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Ergonode\SharedKernel\Domain\Bus\CommandBusInterface;

/**
 * @Route(
 *     name="ergonode_avatar_change",
 *     path="/accounts/{user}/avatar",
 *     methods={"POST"},
 *     requirements={"user"="[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}"},
 *     )
 */
class AvatarChangeAction
{
    private CommandBusInterface $commandBus;

    private FormFactoryInterface $formFactory;

    public function __construct(CommandBusInterface $commandBus, FormFactoryInterface $formFactory)
    {
        $this->commandBus = $commandBus;
        $this->formFactory = $formFactory;
    }

    /**
     * @IsGranted("ACCOUNT_POST_AVATAR")
     *
     * @SWG\Tag(name="Account")
     * @SWG\Parameter(
     *     name="user",
     *     in="path",
     *     required=true,
     *     type="string",
     *     description="User ID",
     * )
     * @SWG\Parameter(
     *     name="upload",
     *     in="formData",
     *     type="file",
     *     description="The field used to upload avatar",
     * )
     * @SWG\Parameter(
     *     name="language",
     *     in="path",
     *     type="string",
     *     required=true,
     *     default="en_GB",
     *     description="Language Code"
     * )
     * @SWG\Response(
     *     response=204,
     *     description="Success"
     * )
     */
    public function __invoke(User $user, Request $request): Response
    {
        $uploadModel = new AvatarUploadModel();

        $form = $this->formFactory->create(AvatarUploadForm::class, $uploadModel, ['method' => Request::METHOD_POST]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $command = new ChangeUserAvatarCommand($user->getId(), $uploadModel->upload);
            $this->commandBus->dispatch($command);
        } else {
            throw new FormValidationHttpException($form);
        }

        return new EmptyResponse();
    }
}
