<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Account\Application\Controller\Api;

use Ergonode\Account\Application\Form\Model\CreateUserFormModel;
use Ergonode\Account\Application\Form\Model\UpdateUserFormModel;
use Ergonode\Account\Application\Form\UserCreateForm;
use Ergonode\Account\Application\Form\UserUpdateForm;
use Ergonode\Account\Domain\Command\User\ChangeUserAvatarCommand;
use Ergonode\Account\Domain\Command\User\ChangeUserPasswordCommand;
use Ergonode\Account\Domain\Command\User\CreateUserCommand;
use Ergonode\Account\Domain\Command\User\UpdateUserCommand;
use Ergonode\Account\Domain\Entity\User;
use Ergonode\Account\Domain\Query\AccountQueryInterface;
use Ergonode\Account\Domain\ValueObject\Email;
use Ergonode\Account\Domain\ValueObject\Password;
use Ergonode\Account\Infrastructure\Builder\PasswordValidationBuilder;
use Ergonode\Account\Infrastructure\Grid\AccountGrid;
use Ergonode\Api\Application\Exception\FormValidationHttpException;
use Ergonode\Api\Application\Exception\ViolationsHttpException;
use Ergonode\Api\Application\Response\CreatedResponse;
use Ergonode\Api\Application\Response\EmptyResponse;
use Ergonode\Api\Application\Response\SuccessResponse;
use Ergonode\Core\Domain\ValueObject\Language;
use Ergonode\Grid\RequestGridConfiguration;
use Ergonode\Grid\Response\GridResponse;
use Ergonode\Multimedia\Domain\Entity\MultimediaId;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Swagger\Annotations as SWG;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\PropertyAccess\Exception\InvalidPropertyPathException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 */
class AccountController extends AbstractController
{
    /**
     * @var AccountGrid
     */
    private $grid;

    /**
     * @var AccountQueryInterface
     */
    private $query;

    /**
     * @var PasswordValidationBuilder
     */
    private $builder;

    /**
     * @var MessageBusInterface
     */
    private $messageBus;

    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * @param AccountGrid               $grid
     * @param AccountQueryInterface     $query
     * @param PasswordValidationBuilder $builder
     * @param MessageBusInterface       $messageBus
     * @param ValidatorInterface        $validator
     */
    public function __construct(
        AccountGrid $grid,
        AccountQueryInterface $query,
        PasswordValidationBuilder $builder,
        MessageBusInterface $messageBus,
        ValidatorInterface $validator
    ) {
        $this->grid = $grid;
        $this->query = $query;
        $this->builder = $builder;
        $this->messageBus = $messageBus;
        $this->validator = $validator;
    }

    /**
     * @Route("/accounts", methods={"GET"})
     *
     * @IsGranted("USER_READ")
     *
     * @SWG\Tag(name="Account")
     * @SWG\Parameter(
     *     name="limit",
     *     in="query",
     *     type="integer",
     *     required=true,
     *     default="50",
     *     description="Number of returned lines",
     * )
     * @SWG\Parameter(
     *     name="offset",
     *     in="query",
     *     type="integer",
     *     required=true,
     *     default="0",
     *     description="Number of start line",
     * )
     * @SWG\Parameter(
     *     name="field",
     *     in="query",
     *     required=false,
     *     type="string",
     *     enum={"id", "label", "code", "hint"},
     *     description="Order field",
     * )
     * @SWG\Parameter(
     *     name="order",
     *     in="query",
     *     required=false,
     *     type="string",
     *     enum={"ASC","DESC"},
     *     description="Order",
     * )
     * @SWG\Parameter(
     *     name="filter",
     *     in="query",
     *     required=false,
     *     type="string",
     *     description="Filter"
     * )
     * @SWG\Parameter(
     *     name="show",
     *     in="query",
     *     required=false,
     *     type="string",
     *     enum={"COLUMN","DATA"},
     *     description="Specify what response should containts"
     * )
     * @SWG\Parameter(
     *     name="language",
     *     in="path",
     *     type="string",
     *     required=true,
     *     default="EN",
     *     description="Language Code",
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Returns users collection",
     * )
     *
     * @ParamConverter(class="Ergonode\Grid\RequestGridConfiguration")
     *
     * @param Language                 $language
     * @param RequestGridConfiguration $configuration
     *
     * @return Response
     */
    public function getUsers(Language $language, RequestGridConfiguration $configuration): Response
    {
        return new GridResponse($this->grid, $configuration, $this->query->getDataSet(), $language);
    }

    /**
     * @Route("/accounts/{user}", methods={"GET"}, requirements={"user"="[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}"})
     *
     * @IsGranted("USER_READ")
     *
     * @SWG\Tag(name="Account")
     * @SWG\Parameter(
     *     name="user",
     *     in="path",
     *     required=true,
     *     type="string",
     *     description="User Id",
     * )
     * @SWG\Parameter(
     *     name="language",
     *     in="path",
     *     type="string",
     *     required=true,
     *     default="EN",
     *     description="Language Code",
     * )
     * @SWG\Response(
     *     response=200,
     *     description="Returns user data",
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Not found",
     * )
     *
     * @ParamConverter(class="Ergonode\Account\Domain\Entity\User")
     *
     * @param User $user
     *
     * @return Response
     */
    public function getUserData(User $user): Response
    {
        $user = $this->query->getUser($user->getId());
        if (empty($user)) {
            throw new NotFoundHttpException('User data not found');
        }

        return new SuccessResponse($user);
    }

    /**
     * @Route("/accounts", methods={"POST"})
     *
     * @IsGranted("USER_CREATE")
     *
     * @SWG\Tag(name="Account")
     * @SWG\Parameter(
     *     name="body",
     *     in="body",
     *     description="Add attribute",
     *     required=true,
     *     @SWG\Schema(ref="#/definitions/account")
     * )
     * @SWG\Parameter(
     *     name="language",
     *     in="path",
     *     type="string",
     *     required=true,
     *     default="EN",
     *     description="Language Code",
     * )
     * @SWG\Response(
     *     response=201,
     *     description="Returns created user id",
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
    public function createUser(Request $request): Response
    {
        try {
            $model = new CreateUserFormModel();
            $form = $this->createForm(UserCreateForm::class, $model);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                /** @var CreateUserFormModel $data */
                $data = $form->getData();

                $command = new CreateUserCommand(
                    $data->firstName,
                    $data->lastName,
                    new Email($data->email),
                    $data->language,
                    $data->password,
                    $data->roleId,
                    $data->isActive
                );
                $this->messageBus->dispatch($command);

                return new CreatedResponse($command->getId());
            }
        } catch (InvalidPropertyPathException $exception) {
            throw new BadRequestHttpException('Invalid JSON format');
        }

        throw new FormValidationHttpException($form);
    }

    /**
     * @Route("/accounts/{user}", methods={"PUT"}, requirements={"user"="[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}"})
     *
     * @IsGranted("USER_UPDATE")
     *
     * @SWG\Tag(name="Account")
     * @SWG\Parameter(
     *     name="user",
     *     in="path",
     *     required=true,
     *     type="string",
     *     description="User Id",
     * )
     * @SWG\Parameter(
     *     name="body",
     *     in="body",
     *     description="Add attribute",
     *     required=true,
     *     @SWG\Schema(ref="#/definitions/account_upd")
     * )
     * @SWG\Parameter(
     *     name="language",
     *     in="path",
     *     type="string",
     *     required=true,
     *     default="EN",
     *     description="Language Code",
     * )
     * @SWG\Response(
     *     response=204,
     *     description="Success"
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Validation error",
     *     @SWG\Schema(ref="#/definitions/validation_error_response")
     * )
     *
     * @ParamConverter(class="Ergonode\Account\Domain\Entity\User")
     *
     * @param User    $user
     * @param Request $request
     *
     * @return Response
     */
    public function updateUser(User $user, Request $request): Response
    {
        try {
            $model = new UpdateUserFormModel();
            $form = $this->createForm(UserUpdateForm::class, $model, ['method' => Request::METHOD_PUT]);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                /** @var UpdateUserFormModel $data */
                $data = $form->getData();

                $command = new UpdateUserCommand(
                    $user->getId(),
                    $data->firstName,
                    $data->lastName,
                    $data->language,
                    $data->roleId,
                    $data->isActive,
                    $data->password
                );
                $this->messageBus->dispatch($command);

                return new EmptyResponse();
            }
        } catch (InvalidPropertyPathException $exception) {
            throw new BadRequestHttpException('Invalid JSON format');
        }

        throw new FormValidationHttpException($form);
    }

    /**
     * @Route("/accounts/{user}/avatar", methods={"PUT"}, requirements={"user"="[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}"})
     *
     * @IsGranted("USER_UPDATE")
     *
     * @SWG\Tag(name="Account")
     * @SWG\Parameter(
     *     name="user",
     *     in="path",
     *     required=true,
     *     type="string",
     *     description="User Id",
     * )
     * @SWG\Parameter(
     *     name="multimedia",
     *     in="formData",
     *     type="string",
     *     description="Multimedia Id",
     * )
     * @SWG\Parameter(
     *     name="language",
     *     in="path",
     *     type="string",
     *     required=true,
     *     default="EN",
     *     description="Language Code",
     * )
     * @SWG\Response(
     *     response=204,
     *     description="Success"
     * )
     *
     * @ParamConverter(class="Ergonode\Account\Domain\Entity\User")
     *
     * @param User    $user
     * @param Request $request
     *
     * @return Response
     */
    public function changeAvatar(User $user, Request $request): Response
    {
        $multimediaId = $request->request->get('multimedia');
        $multimediaId = $multimediaId ? new MultimediaId($multimediaId) : null;
        $command = new ChangeUserAvatarCommand($user->getId(), $multimediaId);
        $this->messageBus->dispatch($command);

        return new EmptyResponse();
    }

    /**
     * @Route("/accounts/{user}/password", methods={"PUT"}, requirements={"user"="[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}"})
     *
     * @IsGranted("USER_UPDATE")
     *
     * @SWG\Tag(name="Account")
     * @SWG\Parameter(
     *     name="user",
     *     in="path",
     *     required=true,
     *     type="string",
     *     description="User Id",
     * )
     * @SWG\Parameter(
     *     name="password",
     *     in="formData",
     *     type="string",
     *     required=true,
     *     description="Password",
     * )
     * @SWG\Parameter(
     *     name="password_repeat",
     *     in="formData",
     *     type="string",
     *     required=true,
     *     description="Password repeat",
     * )
     * @SWG\Parameter(
     *     name="language",
     *     in="path",
     *     type="string",
     *     required=true,
     *     default="EN",
     *     description="Language Code",
     * )
     * @SWG\Response(
     *     response=204,
     *     description="Success"
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Validation error",
     *     @SWG\Schema(ref="#/definitions/validation_error_response")
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Not found",
     * )
     *
     * @ParamConverter(class="Ergonode\Account\Domain\Entity\User")
     *
     * @param User    $user
     * @param Request $request
     *
     * @return Response
     *
     * @todo Why we use user parameter and then we get userId from security (current logged user)?
     */
    public function changePassword(User $user, Request $request): Response
    {
        $data = $request->request->all();
        $constraint = $this->builder->create();
        $violations = $this->validator->validate($data, $constraint);
        $userId = $this->getUser()->getId();

        if ($violations->count() === 0) {
            $command = new ChangeUserPasswordCommand($userId, new Password((string) $data['password']));
            $this->messageBus->dispatch($command);

            return new EmptyResponse();
        }

        throw new ViolationsHttpException($violations);
    }
}
