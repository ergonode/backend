<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Comment\Application\Controller\Api;

use Ergonode\Account\Infrastructure\Provider\AuthenticatedUserProviderInterface;
use Ergonode\Api\Application\Exception\FormValidationHttpException;
use Ergonode\Api\Application\Response\EmptyResponse;
use Ergonode\Comment\Application\Form\Model\CreateCommentFormModel;
use Ergonode\Comment\Application\Form\Model\UpdateCommentFormModel;
use Ergonode\Comment\Application\Form\UpdateCommentForm;
use Ergonode\Comment\Domain\Command\UpdateCommentCommand;
use Ergonode\Comment\Domain\Entity\Comment;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Swagger\Annotations as SWG;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\PropertyAccess\Exception\InvalidPropertyPathException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Ergonode\EventSourcing\Infrastructure\Bus\CommandBusInterface;

/**
 * @Route(
 *     name="ergonode_comment_change",
 *     path="/comments/{comment}",
 *     methods={"PUT"},
 *     requirements={"comment"="[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}"}
 * )
 */
class CommentChangeAction
{
    /**
     * @var CommandBusInterface
     */
    private CommandBusInterface $commandBus;

    /**
     * @var FormFactoryInterface
     */
    private FormFactoryInterface $formFactory;

    /**
     * @var AuthenticatedUserProviderInterface
     */
    private AuthenticatedUserProviderInterface $userProvider;

    /**
     * @param CommandBusInterface                $commandBus
     * @param FormFactoryInterface               $formFactory
     * @param AuthenticatedUserProviderInterface $userProvider
     */
    public function __construct(
        CommandBusInterface $commandBus,
        FormFactoryInterface $formFactory,
        AuthenticatedUserProviderInterface $userProvider
    ) {
        $this->commandBus = $commandBus;
        $this->formFactory = $formFactory;
        $this->userProvider = $userProvider;
    }

    /**
     * @SWG\Tag(name="Comment")
     * @SWG\Parameter(
     *     name="language",
     *     in="path",
     *     type="string",
     *     required=true,
     *     default="EN",
     *     description="Language Code",
     * )
     *
     * @SWG\Parameter(
     *     name="comment",
     *     in="path",
     *     type="string",
     *     required=true,
     *     description="Comment Id",
     * )
     * @SWG\Parameter(
     *     name="body",
     *     in="body",
     *     description="Comment body",
     *     required=true,
     *     @SWG\Schema(ref="#/definitions/comment_update")
     * )
     * @SWG\Response(
     *     response=204,
     *     description="Update category",
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Validation error",
     *     @SWG\Schema(ref="#/definitions/validation_error_response")
     * )
     *
     * @ParamConverter(class="Ergonode\Comment\Domain\Entity\Comment")
     *
     * @param Comment $comment
     * @param Request $request
     *
     * @return Response
     *
     * @throws \Exception
     */
    public function __invoke(Comment $comment, Request $request): Response
    {
        if (!$comment->getAuthorId()->isEqual($this->userProvider->provide()->getId())) {
            throw new AccessDeniedException();
        }

        try {
            $model = new UpdateCommentFormModel();
            $form = $this->formFactory->create(UpdateCommentForm::class, $model, ['method' => Request::METHOD_PUT]);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                /** @var CreateCommentFormModel $data */
                $data = $form->getData();
                $command = new UpdateCommentCommand(
                    $comment->getId(),
                    $data->content
                );
                $this->commandBus->dispatch($command);

                return new EmptyResponse();
            }
        } catch (InvalidPropertyPathException $exception) {
            throw new BadRequestHttpException('Invalid JSON format');
        }

        throw new FormValidationHttpException($form);
    }
}
