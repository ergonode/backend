<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Note\Application\Request\ParamConverter;

use Ergonode\Note\Domain\Entity\Note;
use Ergonode\Note\Domain\Entity\NoteId;
use Ergonode\Note\Domain\Repository\NoteRepositoryInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 */
class NoteParamConverter implements ParamConverterInterface
{
    /**
     * @var NoteRepositoryInterface
     */
    private $repository;

    /**
     * @param NoteRepositoryInterface $repository
     */
    public function __construct(NoteRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * {@inheritDoc}
     */
    public function apply(Request $request, ParamConverter $configuration): void
    {
        $parameter = $request->get('note');

        if (null === $parameter) {
            throw new BadRequestHttpException('Route parameter "note" is missing');
        }

        if (!NoteId::isValid($parameter)) {
            throw new BadRequestHttpException('Invalid note ID format');
        }

        $entity = $this->repository->load(new NoteId($parameter));

        if (null === $entity) {
            throw new NotFoundHttpException(sprintf('Note by ID "%s" not found', $parameter));
        }

        $request->attributes->set($configuration->getName(), $entity);
    }

    /**
     * {@inheritDoc}
     */
    public function supports(ParamConverter $configuration): bool
    {
        return Note::class === $configuration->getClass();
    }
}
