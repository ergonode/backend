<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Comment\Application\Controller\Api;

use Ergonode\Api\Application\Response\EmptyResponse;
use Ergonode\Comment\Domain\Command\DeleteCommentCommand;
use Ergonode\Comment\Domain\Entity\Comment;
use Ergonode\Core\Application\Provider\AuthenticatedUserProviderInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * @Route(
 *     name="ergonode_comment_delete",
 *     path="/comments/{comment}",
 *     methods={"DELETE"},
 *     requirements={"comment"="[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}"}
 * )
 */
class CommentDeleteAction
{
    /**
     * @var MessageBusInterface
     */
    private $messageBus;

    /**
     * @var AuthenticatedUserProviderInterface
     */
    private $userProvider;

    /**
     * @param MessageBusInterface                $messageBus
     * @param AuthenticatedUserProviderInterface $userProvider
     */
    public function __construct(MessageBusInterface $messageBus, AuthenticatedUserProviderInterface $userProvider)
    {
        $this->messageBus = $messageBus;
        $this->userProvider = $userProvider;
    }

    /**
     * @SWG\Tag(name="Comment")
     * @SWG\Parameter(
     *     name="comment",
     *     in="path",
     *     type="string",
     *     required=true,
     *     description="Comment ID",
     * )
     * @SWG\Response(
     *     response=204,
     *     description="Success"
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Not found",
     * )
     * @SWG\Response(
     *     response="409",
     *     description="Existing relationships"
     * )
     *
     * @ParamConverter(class="Ergonode\Comment\Domain\Entity\Comment")
     *
     * @param Comment $comment
     *
     * @return Response
     */
    public function __invoke(Comment $comment): Response
    {
        if (!$comment->getAuthorId()->isEqual($this->userProvider->provide()->getId())) {
            throw new AccessDeniedException();
        }

        $command = new DeleteCommentCommand($comment->getId());
        $this->messageBus->dispatch($command);

        return new EmptyResponse();
    }
}
