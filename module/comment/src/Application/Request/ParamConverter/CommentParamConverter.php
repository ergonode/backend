<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Comment\Application\Request\ParamConverter;

use Ergonode\Comment\Domain\Entity\Comment;
use Ergonode\Comment\Domain\Entity\CommentId;
use Ergonode\Comment\Domain\Repository\CommentRepositoryInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 */
class CommentParamConverter implements ParamConverterInterface
{
    /**
     * @var CommentRepositoryInterface
     */
    private CommentRepositoryInterface $repository;

    /**
     * @param CommentRepositoryInterface $repository
     */
    public function __construct(CommentRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * {@inheritDoc}
     */
    public function apply(Request $request, ParamConverter $configuration): void
    {
        $parameter = $request->get('comment');

        if (null === $parameter) {
            throw new BadRequestHttpException('Route parameter "comment" is missing');
        }

        if (!CommentId::isValid($parameter)) {
            throw new BadRequestHttpException('Invalid comment ID format');
        }

        $entity = $this->repository->load(new CommentId($parameter));

        if (null === $entity) {
            throw new NotFoundHttpException(sprintf('Comment by ID "%s" not found', $parameter));
        }

        $request->attributes->set($configuration->getName(), $entity);
    }

    /**
     * {@inheritDoc}
     */
    public function supports(ParamConverter $configuration): bool
    {
        return Comment::class === $configuration->getClass();
    }
}
