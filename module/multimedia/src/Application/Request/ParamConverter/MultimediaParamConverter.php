<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Multimedia\Application\Request\ParamConverter;

use Ergonode\Multimedia\Domain\Entity\Multimedia;
use Ergonode\SharedKernel\Domain\Aggregate\MultimediaId;
use Ergonode\Multimedia\Domain\Repository\MultimediaRepositoryInterface;
use League\Flysystem\FilesystemInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 */
class MultimediaParamConverter implements ParamConverterInterface
{
    /**
     * @var MultimediaRepositoryInterface
     */
    private MultimediaRepositoryInterface $repository;

    /**
     * @var FilesystemInterface
     */
    private FilesystemInterface $multimediaStorage;

    /**
     * @param MultimediaRepositoryInterface $repository
     * @param FilesystemInterface           $multimediaStorage
     */
    public function __construct(
        MultimediaRepositoryInterface $repository,
        FilesystemInterface $multimediaStorage
    ) {
        $this->repository = $repository;
        $this->multimediaStorage = $multimediaStorage;
    }

    /**
     * {@inheritDoc}
     */
    public function apply(Request $request, ParamConverter $configuration): void
    {
        $parameter = $request->get('multimedia');

        if (null === $parameter) {
            throw new BadRequestHttpException('Request parameter "multimedia" is missing');
        }

        if (!MultimediaId::isValid($parameter)) {
            throw new BadRequestHttpException('Invalid multimedia ID');
        }

        $entity = $this->repository->load(new MultimediaId($parameter));

        if (null === $entity) {
            throw new NotFoundHttpException(sprintf('Multimedia by ID "%s" not found', $parameter));
        }

        if (!$this->multimediaStorage->has($entity->getFileName())) {
            throw new ConflictHttpException('The file does not exist.');
        }

        $request->attributes->set($configuration->getName(), $entity);
    }

    /**
     * {@inheritDoc}
     */
    public function supports(ParamConverter $configuration): bool
    {
        return Multimedia::class === $configuration->getClass();
    }
}
