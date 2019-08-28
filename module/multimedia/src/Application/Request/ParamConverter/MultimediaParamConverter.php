<?php

/**
 * Copyright © Bold Brand Commerce Sp. z o.o. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types = 1);

namespace Ergonode\Multimedia\Application\Request\ParamConverter;

use Ergonode\Multimedia\Domain\Entity\Multimedia;
use Ergonode\Multimedia\Domain\Entity\MultimediaId;
use Ergonode\Multimedia\Domain\Repository\MultimediaRepositoryInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Request\ParamConverter\ParamConverterInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 */
class MultimediaParamConverter implements ParamConverterInterface
{
    /**
     * @var MultimediaRepositoryInterface
     */
    private $repository;

    /**
     * @param MultimediaRepositoryInterface $repository
     */
    public function __construct(MultimediaRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param Request        $request
     * @param ParamConverter $configuration
     *
     * @return void
     */
    public function apply(Request $request, ParamConverter $configuration): void
    {
        $id = $request->get('multimedia');


        if (null === $id) {
            throw new BadRequestHttpException('MultimediaId is missing');
        }

        if (!MultimediaId::isValid($id)) {
            throw new BadRequestHttpException('Invalid uuid format');
        }

        $import = $this->repository->load(new MultimediaId($id));

        if (null === $import) {
            throw new NotFoundHttpException('Multimedia file not found.');
        }

        $request->attributes->set($configuration->getName(), $import);
    }

    /**
     * @param ParamConverter $configuration
     *
     * @return bool
     */
    public function supports(ParamConverter $configuration): bool
    {
        return Multimedia::class === $configuration->getClass();
    }
}
